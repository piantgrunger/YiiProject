<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "books".
 *
 * @property int $id
 * @property string $book_name
 * @property string|null $description
 * @property string|null $isbn
 */
class Books extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'books';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['description'], 'string'],
            [['book_name'], 'string', 'max' => 255],
            [['isbn'], 'string', 'max' => 13],
            [['isbn'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'book_name' => 'Book Name',
            'description' => 'Description',
            'isbn' => 'Isbn',
        ];
    }

    public function getIsAvailable() {
        return count($this->currentRentingUser) === 0;
    }

    public function getCurrentRentingUser()
    {
        $now =  Date('Y-m-d');

        return $this->hasMany(Rental::class, ['book_id'=>'id'])->where(['>','rent_start',  $now])
            ->andWhere('returned_at is null');
    }

 
}
