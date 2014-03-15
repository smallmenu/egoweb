<?php

/**
 * This is the model class for table "graphs".
 *
 * The followings are the available columns in table 'graphs':
 * @property integer $id
 * @property integer $interviewId
 * @property integer $expressionId
 * @property string $json
 * @property string $nodes
 */
class Graph extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Graph the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'graphs';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('interviewId, expressionId, json, name, nodes', 'required'),
			array('interviewId, expressionId', 'numerical', 'integerOnly'=>true),
			array('params', 'default', "value"=>''),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, interviewId, expressionId, json, nodes', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'interviewId' => 'Interview',
			'expressionId' => 'Expression',
			'json' => 'Json',
			'nodes' => 'Nodes',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('interviewId',$this->interviewId);
		$criteria->compare('expressionId',$this->expressionId);
		$criteria->compare('json',$this->json,true);
		$criteria->compare('nodes',$this->nodes,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}