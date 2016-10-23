# -*- coding: utf-8 -*-
from BaseHTTPServer import BaseHTTPRequestHandler,HTTPServer
from urlparse import urlparse, parse_qs
import json

class lhcHandler(BaseHTTPRequestHandler):
	
	settings = None
	bot = None
	
	#Handler for the GET requests
	def do_GET(self):
	
		sendReply = True
		if self.path.endswith(".ico"):
				sendReply = False
	
		if sendReply == False:
			return
								
	 	query_components = parse_qs(urlparse(self.path).query)
	 	
	 	if 'id' not in query_components:
	 		self.send_response(200)
			self.send_header('Content-type',"text/plain")
			self.end_headers()
			self.wfile.write('{"error":true,"msg":"id has to be provided"}')
	 		return 
	 	
	 	if 'sh' not in query_components:
	 		self.send_response(200)
			self.send_header('Content-type',"text/plain")
			self.end_headers()
			self.wfile.write('{"error":true,"msg":"Secret hash [sh] has to be provided"}')
	 		return 

	 	if ''.join(query_components["sh"]) != self.settings['SECRET_HASH']:
	 		self.send_response(200)
			self.send_header('Content-type',"text/plain")
			self.end_headers()
			self.wfile.write('{"error":true,"msg":"Invalid secret hash"}')
	 		return 
	 		
	 	if 'qd' in query_components:
	 		try:
				self.bot.deleteQuestion(''.join(query_components["id"]),''.join(query_components["qd"]));
				self.send_response(200)
				self.send_header('Content-type',"text/plain")
				self.end_headers()
				self.wfile.write(json.dumps({"error":False,"msg":"question has been deleted"}))
				return
	
			except IOError:
				self.send_error(404,'File Not Found: %s' % self.path)
	 		
	 	if 'qq' in query_components and 'qa' in query_components:
	 		try:
				self.bot.addAnswer(''.join(query_components["id"]),''.join(query_components["qq"]),''.join(query_components["qa"]));
				self.send_response(200)
				self.send_header('Content-type',"text/plain")
				self.end_headers()
				self.wfile.write(json.dumps({"error":False,"msg":"question with answer has been added"}))
				return
	
			except IOError:
				self.send_error(404,'File Not Found: %s' % self.path)
	 
		if 'q' in query_components:
			try:	
				self.send_response(200)
				self.send_header('Content-type',"text/plain")
				self.end_headers()
				self.wfile.write(json.dumps({'error':False,'msg':self.bot.getAnswer(''.join(query_components["id"]),''.join(query_components["q"])).text}))  
				return
	
			except IOError:
				self.send_error(404,'File Not Found: %s' % self.path)
				
		self.send_response(200)
		self.send_header('Content-type',"text/plain")
		self.end_headers()
		self.wfile.write('{"error":true,"msg":"unknown operation"}')
		
		return