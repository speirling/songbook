<?php
App::uses('AppModel', 'Model');
/**
 * PlaylistSet Model
 *
 * @property Set $Set
 * @property Playlist $Playlist
 */
class PlaylistSet extends AppModel {

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'set_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'playlist_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'order' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Set' => array(
			'className' => 'Set',
			'foreignKey' => 'set_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Playlist' => array(
			'className' => 'Playlist',
			'foreignKey' => 'playlist_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
