/*********************************
called like this:

import com.nolimit.utils.WebRequestFromFacebook;

var blob:Object = new Object();
blob.score = 90123;
blob.sex = "male";

var url = 'http://rzygler.dev.nolimit.com/friendmatch/game/testpost2';

var request:Object = new WebRequestFromFacebook(url, parameters, blob);
request.addEventListener('complete', webRequestComplete, false, 0, true);
request.addEventListener('error', webRequestError, false, 0, true);
request.post();

function webRequestComplete(e:Event)
{
    txtArea1.appendText("post is done. ");
}
function webRequestError(e:Event)
{
    txtArea1.appendText("post: something went wrong");
}
*
*
*
*/
package com.nolimit.utils {

	import flash.events.*;
	import flash.net.*;
	import flash.net.URLRequest;


	public class WebRequestFromFacebook extends EventDispatcher {

		protected var fb_sig:String = "";
		protected var fb_sig_api_key:String = "";
		protected var fb_sig_user:String = "";
		protected var hash:String = "";
		protected var parameters:Object;
		protected var postUrl:String = "";
		protected var postVars:Object;

		public function WebRequestFromFacebook(url:String, params:Object, vars:Object )
		{
			this.parameters = params;
			this.postUrl = url;
			this.postVars = vars;

			var keyArray:Array = new Array();
			for (key in parameters)
			{
    			keyArray.push(key);
			}
			keyArray = keyArray.sort();

			while (keyArray.length > 0) {
				var key:String = keyArray.shift();

				if (key == "fb_sig_api_key")
				{
					 fb_sig_api_key = parameters[key];
				}
				if (key == "fb_sig")
				{
					fb_sig = parameters[key];
				}
				if (key == "fb_sig_user")
				{
					fb_sig_user = parameters[key];
				}
				if (key != "fb_sig" && key.substring(0, 6) == "fb_sig") {
					var param:String = parameters[key];
					hash += key.substr(7) + "=" + param;
				}
			}
		}

		public function getFbSig():String
		{
			return fb_sig;
		}

		public function getFbSigApiKey():String
		{
			return fb_sig_api_key;
		}

		public function getHash():String
		{
			return hash;
		}

		public function post()
		{
			var url:String = postUrl;

			var request:URLRequest = new URLRequest(url);
			request.method = URLRequestMethod.POST;


			var requestVars:URLVariables = new URLVariables();
				requestVars.fb_sig_api_key = this.fb_sig_api_key;
				requestVars.hash = this.hash;
				requestVars.fb_sig = this.fb_sig;
				requestVars.uid = this.fb_sig_user;

			for (var key in postVars)
			{
				requestVars[key] = postVars[key];
			}

			request.data = requestVars;

			var urlLoader:URLLoader = new URLLoader();
			urlLoader.dataFormat = URLLoaderDataFormat.TEXT;
			urlLoader.addEventListener(Event.COMPLETE, completePost);
			urlLoader.addEventListener(IOErrorEvent.IO_ERROR, onIOErrorPost);

			try {
				urlLoader.load(request);
			} catch (e:Error) {
				trace(e);
			}


		}
		function onIOErrorPost(event:Event)
		{
			dispatchEvent(new Event("error"));
		}

		function completePost(event:Event)
		{
			dispatchEvent(new Event("complete"));
		}

	}
}