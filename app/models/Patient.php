<?php

namespace app\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "patients".
 *
 * @property int $id
 * @property string $name
 * @property string|null $birthday
 * @property string|null $phone
 * @property string|null $address
 * @property int|null $polyclinic_id
 * @property int|null $treatment_id
 * @property int|null $status_id
 * @property int|null $form_disease_id
 * @property string|null $created
 * @property int|null $created_by
 * @property string|null $updated
 * @property int|null $updated_by
 * @property string|null $diagnosis_date
 * @property string|null $recovery_date
 * @property string|null $analysis_date
 * @property int|null $source_id
 *
 * @property FormDiseases $formDisease
 * @property Patient $source
 * @property Patient[] $patients
 * @property Polyclinics $polyclinic
 * @property Statuses $status
 * @property Treatments $treatment
 * @property User $createdBy
 * @property User $updatedBy
 */
class Patient extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'patients';
    }

    public function behaviors()
    {
        return [
            'timestampBehavior' => [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'created',
                'updatedAtAttribute' => 'updated',
                'value' => date('Y-m-d H:i:s'),
            ],
            'blameableBehavior' => [
                'class' => BlameableBehavior::class,
            ]
        ];
    }

    public function beforeSave($insert)
    {
        if ($insert) {
            $user = User::findOne(Yii::$app->user->id);
            $this->polyclinic_id = $user->polyclinic_id;
        }
        return parent::beforeSave($insert);
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['birthday', 'created', 'updated', 'diagnosis_date', 'recovery_date', 'analysis_date'], 'safe'],
            [['birthday', 'diagnosis_date', 'recovery_date', 'analysis_date', 'status_id', 'form_disease_id', 'treatment_id',], 'default', 'value' => null],
            [['treatment_id', 'status_id', 'form_disease_id', 'created_by', 'updated_by', 'source_id'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['phone'], 'string', 'max' => 50],
            [['address'], 'string', 'max' => 512],
            [['birthday', 'diagnosis_date', 'recovery_date', 'analysis_date'], 'convertDate'],
            [['form_disease_id'], 'exist', 'skipOnError' => true, 'targetClass' => FormDiseases::className(), 'targetAttribute' => ['form_disease_id' => 'id']],
            [['source_id'], 'exist', 'skipOnError' => true, 'targetClass' => Patient::className(), 'targetAttribute' => ['source_id' => 'id']],
            [['polyclinic_id'], 'exist', 'skipOnError' => true, 'targetClass' => Polyclinics::className(), 'targetAttribute' => ['polyclinic_id' => 'id']],
            [['status_id'], 'exist', 'skipOnError' => true, 'targetClass' => Statuses::className(), 'targetAttribute' => ['status_id' => 'id']],
            [['treatment_id'], 'exist', 'skipOnError' => true, 'targetClass' => Treatments::className(), 'targetAttribute' => ['treatment_id' => 'id']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updated_by' => 'id']],
        ];
    }

    /**
     * Конвертация дат в формат БД.
     *
     * @param $attribute
     * @return void
     */
    public function convertDate($attribute)
    {
        $this->$attribute = $this->$attribute ? date('Y-m-d', strtotime($this->$attribute)) : null;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'ФИО',
            'birthday' => 'Дата рождения',
            'phone' => 'Номер телефона',
            'address' => 'Адрес',
            'polyclinic_id' => 'Поликлиника',
            'treatment_id' => 'Форма лечения',
            'status_id' => 'Статус',
            'form_disease_id' => 'Течение болезни',
            'created' => 'Создана',
            'created_by' => 'Создана',
            'updated' => 'Изменена',
            'updated_by' => 'Измененв',
            'diagnosis_date' => 'Диагноз',
            'recovery_date' => 'Выздоровление',
            'analysis_date' => 'Анализ',
            'source_id' => 'От кого заразился',
        ];
    }

    /**
     * Fields for API.
     *
     * @return array
     */
    public function fields(): array
    {
        return [
            'name',
            'birthday',
            'phone',
            'polyclinic',
            'status',
            'treatment',
            'formDisease',
            'updated',
            'diagnosis_date',
            'recovery_date',
        ];
    }

    /**
     * Gets query for [[FormDisease]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFormDisease()
    {
        return $this->hasOne(FormDiseases::className(), ['id' => 'form_disease_id']);
    }

    /**
     * Gets query for [[Source]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSource()
    {
        return $this->hasOne(Patient::className(), ['id' => 'source_id']);
    }

    /**
     * Gets query for [[Patients]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPatients()
    {
        return $this->hasMany(Patient::className(), ['source_id' => 'id']);
    }

    /**
     * Gets query for [[Polyclinic]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPolyclinic()
    {
        return $this->hasOne(Polyclinics::className(), ['id' => 'polyclinic_id']);
    }

    /**
     * Gets query for [[Status]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStatus()
    {
        return $this->hasOne(Statuses::className(), ['id' => 'status_id']);
    }

    /**
     * Gets query for [[Treatment]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTreatment()
    {
        return $this->hasOne(Treatments::className(), ['id' => 'treatment_id']);
    }

    /**
     * Gets query for [[CreatedBy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    /**
     * Gets query for [[UpdatedBy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }
}
