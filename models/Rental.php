<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_rents_book".
 *
 * @property int $id
 * @property int $user_id
 * @property int $book_id
 * @property string $rent_start
 * @property string $rent_end
 * @property string|null $returned_at
 */
class Rental extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_rents_book';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'book_id', 'rent_start', 'rent_end'], 'required'],
            [['user_id', 'book_id'], 'integer'],
            [['rent_start', 'rent_end', 'returned_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'book_id' => 'Book ID',
            'rent_start' => 'Rent Startet',
            'rent_end' => 'Due at',
            'returned_at' => 'Returned At',
        ];
    }

    public function getBook()
    {
        return $this->hasOne(Books::className(),['id'=>'book_id']);
    }
}
