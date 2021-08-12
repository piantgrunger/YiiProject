const axios = require('axios');
var expect = require('chai').expect;

describe('API is operational', () => {
    axios.defaults.baseURL = 'http://localhost:8000/api';
    axios.defaults.headers.common['Authorization'] = 'Bearer aivaikei0aashue0aep5baedoy1evopaeSeig1toh7Ahshie7FuXeilooZ2phoo9wusif0ohe3DieMee';

    it('Should send unauthorized without API token', async () => {
        try {
            const data = await axios.get('/user', {'headers': {'Authorization': ''}});
        } catch (e) {
            console.warn(e.message)
            expect(e.message).equals("Request failed with status code 401")
        }
    }).timeout(10000);


    it('Should send correct user info with API token', async () => {
        const data = await axios.get('/user');
        expect(data.data).to.deep.include({"id":2,"first_name":"Robin","last_name":"Heller","email":"admin@heller.pw","is_admin":1,"api_token":"aivaikei0aashue0aep5baedoy1evopaeSeig1toh7Ahshie7FuXeilooZ2phoo9wusif0ohe3DieMee","is_disabled":0})
    }).timeout(10000);

    it('Should send correct user count via API', async () => {
        const data = await axios.get('/users');
        expect(data.data.totalCount).eql(5);
    }).timeout(10000);

    it('Should send correct due rental count via API', async () => {
        const data = await axios.get('/due_rentals');
        expect(data.data.totalCount).eql(1);
    }).timeout(10000);

    it('Should send correct book count via API, while paginating using MAX', async () => {
        const data = await axios.get('/books');
        expect(data.data.totalCount).eql(548);
        expect(data.data.data.length).eql(50);
    }).timeout(10000);

    it('Should send correct book count via API, while paginating using custom parameter', async () => {
        const data = await axios.get('/books?limit=10');
        expect(data.data.totalCount).eql(548);
        expect(data.data.data.length).eql(10);
    }).timeout(10000);

    it('Should handle offset parameters correctly (e.g. change collection completely)', async () => {
        const data = await axios.get('/books?limit=10');
        expect(data.data.totalCount).eql(548);
        expect(data.data.data.length).eql(10);
        const data2 = await axios.get('/books?limit=10&offset=10');
        expect(data2.data.totalCount).eql(548);
        expect(data2.data.data.length).eql(10);

        expect(data2.data.data).not.eql(data.data.data);
    }).timeout(10000);

    it('Should be able to modify due rental count via API', async () => {
        const old = await axios.get('/due_rentals');
        var oldCount = old.data.totalCount;
        await axios.post('/end_rental/5');
        const data = await axios.get('/due_rentals');
        expect(data.data.totalCount).eql(oldCount - 1);
    }).timeout(10000);

    it('Should be able to disable user via API', async () => {
        const data = await axios.get('/users');
        expect(data.data.data[0].id).eql(1);
        expect(data.data.data[0].is_disabled).eql(0);
        await axios.post('users/1/disable');
        const dataChanged = await axios.get('/users');
        expect(dataChanged.data.data[0].id).eql(1);
        expect(dataChanged.data.data[0].is_disabled).eql(1);
    }).timeout(10000);

}).timeout(10000);
