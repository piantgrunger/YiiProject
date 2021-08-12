<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "users".
 *
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property string $password
 * @property int $is_admin
 * @property string|null $api_token
 * @property int $is_disabled
 */
class Users extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    /**
     * {@inheritdoc}
     */

    public $username;
    public $authKey;
    public $accessToken;

    

    public static function tableName()
    {
        return 'users';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['password'], 'required'],
            [['is_admin', 'is_disabled'], 'integer'],
            [['first_name', 'last_name','username'], 'string', 'max' => 100],
            [['email'], 'string', 'max' => 150],
            [['password'], 'string', 'max' => 60],
            [['api_token'], 'string', 'max' => 80],
            [['api_token'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'email' => 'Email',
            'password' => 'Password',
            'is_admin' => 'Is Admin',
            'api_token' => 'Api Token',
            'is_disabled' => 'Is Disabled',
        ];
    }
      /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
     
        return static::findOne(['api_token' => $token]);
   
        return null;
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['email' => $username]);
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
      //  return $this->authKey;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
      //  return $this->authKey === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password);
    }

    public function getActiveRentedBooks()
    {
        $now = Date('Y-m-d');
        return Rental::find()
            ->where(['>','rent_start',  $now])
            ->andwhere('returned_at is null')
            ->andWhere('user_id ='.$this->id)
            ->all();
    }
}
