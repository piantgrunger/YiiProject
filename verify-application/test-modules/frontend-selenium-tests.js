var expect = require('chai').expect;
require('chromedriver');

const {Builder, By, until, Key} = require('selenium-webdriver');

describe('Frontend is Operational', () => {
    const driver = new Builder().forBrowser('chrome').build();

    driver.manage().deleteAllCookies();

    it('Should have "Library" in title', async () => {
        await driver.get('http://localhost:8080');
        const title = await driver.getTitle();
        expect(title).to.equal('Library');
    }).timeout(10000);

    it('Should serve up books page', async () => {
        await driver.get('http://localhost:8080/books');
        await driver.wait(until.elementLocated(By.tagName('table')))
    }).timeout(10000);

    it('Should have 10 table rows inside of tbody', async () => {
        await driver.get('http://localhost:8080/books');
        var elems = await driver.findElements(By.css('tbody > tr'))
        expect(elems.length).to.equal(10);
        await driver.wait(until.elementLocated(By.tagName('table')))
    }).timeout(10000);

    it('Should paginate the books to more than 10 pages', async () => {
        await driver.get('http://localhost:8080/books');
        var elems = await driver.findElements(By.css('a.page-link'))
        expect(elems.length).to.be.greaterThan(10);
        await driver.wait(until.elementLocated(By.tagName('table')))
    }).timeout(10000);

    it('Should not serve book detail links to logged in users', async () => {
        await driver.get('http://localhost:8080/books');
        var elems = await driver.findElements(By.css('tbody > tr a'))
        expect(elems.length).to.equal(0);
    }).timeout(10000);

    it('Should not serve search slot to logged in users', async () => {
        await driver.get('http://localhost:8080/books');
        const elems = await driver.findElements(By.name('q'));
        expect(elems).length(0);
    }).timeout(10000);


    it('Should redirect guest-requests to book detail', async () => {
        await driver.get('http://localhost:8080/books/1');
        const url = await driver.getCurrentUrl();
        expect(url).to.equal('http://localhost:8080/login');
    }).timeout(10000);

    it('Should redirect guest-requests to logout', async () => {
        await driver.get('http://localhost:8080/logout');
        const url = await driver.getCurrentUrl();
        expect(url).to.equal('http://localhost:8080/login');
    }).timeout(10000);

    it('Should redirect guest-requests to my-rentals', async () => {
        await driver.get('http://localhost:8080/my-rentals');
        const url = await driver.getCurrentUrl();
        expect(url).to.equal('http://localhost:8080/login');
    }).timeout(10000);

    it('Should serve up a login page for guests', async () => {
        await driver.get('http://localhost:8080/login');
        await driver.wait(until.elementLocated(By.name('email')));
        await driver.wait(until.elementLocated(By.name('password')));
    }).timeout(10000);

    it('Should allow me to log in', async () => {
        const loginField = await driver.findElement(By.name('email'))
        const passwordField = await driver.findElement(By.name('password'))
        const submitBtn = await driver.findElement(By.css('form button'))
        await loginField.sendKeys('robin@heller.pw');
        await passwordField.sendKeys('testtest');
        await submitBtn.click();
        driver.wait(until.urlIs('http://localhost:8080/my-rentals'))
    }).timeout(10000);

    it('Should serve book detail links to logged in users', async () => {
        await driver.get('http://localhost:8080/books');
        var elems = await driver.findElements(By.css('tbody > tr a'))
        expect(elems.length).to.equal(10);
        var elems = await driver.findElements(By.css('[data-perftest="detail-link"]'))
        expect(elems.length).to.equal(10);
    }).timeout(10000);

    it('Should serve search slot to logged in users', async () => {
        await driver.get('http://localhost:8080/books');
        const elems = await driver.findElements(By.name('q'));
        expect(elems).length(1);
    }).timeout(10000);

    it('Searching for gibberish should return no results', async () => {
        await driver.get('http://localhost:8080/books');
        var searchSlot = await driver.findElement(By.name('q'))
        await searchSlot.sendKeys('ThisWontEverBeFoundBlaaaa', Key.RETURN)
        await driver.wait(until.elementLocated(By.css('.no-results')))
        const elems = await driver.findElements(By.css('.no-results'));
        expect(elems).length(1);
    }).timeout(10000);

    it('Searching for "Summer" yields 4 table content rows', async () => {
        await driver.get('http://localhost:8080/books');
        var searchSlot = await driver.findElement(By.name('q'))
        await searchSlot.sendKeys('Summer', Key.RETURN)
        await driver.wait(until.elementLocated(By.css('tbody > tr')))
        const elems = await driver.findElements(By.css('tbody > tr'));
        expect(elems).length(4);
    }).timeout(10000);

    it('User can rent a book', async () => {
        await driver.get('http://localhost:8080/books/50');
        await driver.wait(until.elementLocated(By.tagName('button')))
        const button = await driver.findElement(By.tagName('button'));
        button.click();
        await driver.wait(until.elementLocated(By.css('h4')))
        const url = await driver.getCurrentUrl();
        expect(url).to.equal('http://localhost:8080/my-rentals?added=1');
        const successElem = await driver.findElement(By.tagName('h4'));
        const msg = await successElem.getAttribute('textContent');
        expect(msg).to.equal('Successfully added new rental!');
    }).timeout(10000);

    it('Same book does not offer a rent option anymore then', async () => {
        await driver.get('http://localhost:8080/books/50');
        const elems = await driver.findElements(By.css('button'));
        expect(elems).length(0);
    }).timeout(10000);
    //
    //
    it('User cannot rent more than 5 books', async () => {
        await driver.get('http://localhost:8080/books/51');
        await driver.wait(until.elementLocated(By.tagName('button')))
        const button = await driver.findElement(By.tagName('button'));
        button.click();
        await driver.wait(until.elementLocated(By.css('.alert li')))
        const url = await driver.getCurrentUrl();
        expect(url).to.equal('http://localhost:8080/books/51');
        const errorElem = await driver.findElement(By.css('.alert li'));
        const msg = await errorElem.getAttribute('textContent');
        expect(msg).to.equal('Sorry, but you have reached the limit of 5 actively rented books');
    }).timeout(10000);

    it('Uses only water.css', async () => {
        await driver.get('http://localhost:8080/books/51');
        await driver.wait(until.elementLocated(By.tagName('link')))
        const linkElems = await driver.findElements(By.tagName('link'));
        expect(linkElems.length).to.equal(1);
        const linkElem = linkElems[0];
        const linkSrc = await linkElem.getAttribute('href')
        expect(linkSrc).to.equal('https://cdn.jsdelivr.net/npm/water.css@2/out/water.css');
    }).timeout(10000);

    it('perftest classes set on the pagination', async () => {
        await driver.get('http://localhost:8080/books');
        await driver.wait(until.elementLocated(By.css('[data-perftest-pagination]')))
        const linkElems = await driver.findElements(By.tagName('[data-perftest-pagination]'));
        expect(linkElems.length).gt(4);
    }).timeout(10000);

    after(async () => driver.quit());
}).timeout(10000);
