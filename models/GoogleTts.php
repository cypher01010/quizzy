<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "google_tts".
 *
 * @property integer $id
 * @property string $text
 * @property string $language
 * @property string $file_name
 */
class GoogleTts extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'google_tts';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['text', 'language', 'file_name'], 'required'],
			[['text'], 'string', 'max' => 512],
			[['language'], 'string', 'max' => 32],
			[['file_name'], 'string', 'max' => 64]
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'text' => 'Text',
			'language' => 'Language',
			'file_name' => 'File Name',
		];
	}

	/**
	 * Call the Google TTS and generate an audio file
	 *
	 * @param $text
	 * @param $language
	 * @param $path
	 * @param $filename
	 * @param $config (optional)
	 * @return filename with extension
	 */
	public function translate($text, $language, $path, $filename, $config = array())
	{
		$text = stripslashes(trim($text));

		$ttsURL = 'https://api.voicerss.org/?';
		$tts = $this->voicerssCurl($ttsURL, [
			'key' => $config['apiKey'],
			'src' => $text,
			'hl' => $config['language'],
			'c' => $config['codec'],
		]);

		//mp3
		$filePath = $path . DIRECTORY_SEPARATOR . $filename . '.mp3';
		$file = fopen($filePath, "a");
		fwrite($file, $tts);
		fclose($file);

		return $filename;
	}

	/**
	 * Method to encode an MP3 file to Ogg Vorbis format
	 *
	 * @param string $file
	 * @param bool string $delete
	 * @return void
	 */
	public function mp32OggFile($file, $delete = FALSE)
	{
		if(file_exists($file))
		{
			//$filename = basename($file);
			//$path = str_replace($filename, "",$file);
			//$res = @system("/usr/bin/mp32ogg $file $path");
			$res = @system("/usr/bin/mp32ogg $file");
			//shell_exec("/usr/bin/mp32ogg $file");
			
			if($delete == TRUE)
			{
				unlink($file);
			}
			return $res;
		}
		else {
			throw new Exception("File $file could not be found for conversion!");
		}
	}

	/**
	 * Curl
	 *
	 * @param $url
	 * @return content 
	 */
	function getCurl($url)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.1.2) Gecko/20090729 Firefox/3.5.2 GTB5');
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
		$response = curl_exec($ch);
		curl_close($ch);
		return $response;
	}

	private function voicerssCurl($url, $params)
	{
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.1.2) Gecko/20090729 Firefox/3.5.2 GTB5');
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POST, true);

		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
		$tts = curl_exec($ch);
		curl_close($ch);

		return $tts;
	}

	/** 
	 * Generate a filename for google TTS
	 *
	 * @return filename
	 */
	public function generateFilename()
	{
		$min = 20;
		$max = 30;
		$charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
	
		$char = '';
		$end = mt_rand ($min, $max);
		for ($start=0; $start < $end; $start++) $char .= $charset[(mt_rand(0,(strlen($charset)-1)))];

		$filename = $char . '-' . time();

		return $filename;
	}

	/**
	 * Search for TTS Audio
	 * 
	 * @param $text
	 * @param $language
	 * @return object|NULL
	 */
	public function searchTTSAudio($text, $language)
	{
		$sql =
		'
			SELECT
				google_tts.*
			FROM
				google_tts
			WHERE
				google_tts.text =  \'' . $text . '\'
					AND
				google_tts.language =  \'' . $language . '\'
		';

		$response = self::findBySql($sql)->one();
		if(isset($response) && $response != null) {
			return $response;
		}

		return NULL;
	}

	/**
	 * Add a new record into db
	 *
	 * @param $text
	 * @param $language
	 * @param $filename
	 * @return id
	 */
	public function addRecord($text, $language, $filename)
	{
		$this->text = $text;
		$this->language = $language;
		$this->file_name = $filename;
		$this->insert();
		return $this->id;
	}
}