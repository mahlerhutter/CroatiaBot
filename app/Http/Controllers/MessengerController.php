<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use pimax\FbBotApp;
use pimax\Messages\Message;

class MessengerController extends Controller
{
	public function webhook(){

		$local_verify_token = env('WEBHOOK_VERIFY_TOKEN');
		$hub_verify_token = \Input::get('hub_verify_token'); 

		if($local_verify_token == $hub_verify_token) {

			return \Input::get('hub_challenge');

		}
		else return "Ayn, Bad verify token";
	}

	public function webhook_post(){
		//get message input
		$input=\Input::all();
		//get the receipient ----> see  log if you want 2 see how message is received
		//	\Log::info(print_r($input,1));
		$recipient=$input['entry'][0]['messaging'][0]['sender']['id'];
		//what sender sent to us
		$receivedmessage=strtolower($input['entry'][0]['messaging'][0]['message']['text']);


		//handle input
		//ist ein keyword enthalten?
		if (strpos($receivedmessage, "help")!== false ){
			$text = 'How can I help?';
		}
		// oder messag

		elseif ($receivedmessage == "vienna" || $receivedmessage == "wien"){
			$text = 'Trends in Wien?';
		}

		//ob es in array ist
		elseif (in_array($receivedmessage,["hy","hallo","gutn Tag"])){
			$text = 'Howdy';
		}

		//Check und gib zurück	
		elseif (in_array($receivedmessage,["zadar","split","dubrovnik"])){
			$text = 'Tips for ' .$receivedmessage.':';
		}

		else $text="Didnt get it";

		//send message


		//Create Bot instance
		$token = env('PAGE_ACCESS_TOKEN');
		$bot = new FbBotApp($token);



		$recipient=$input['entry'][0]['messaging'][0]['sender']['id'];
		$message=new Message($recipient, $text);
		$bot -> send($message);


	}
 }
 
 