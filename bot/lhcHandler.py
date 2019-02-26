# -*- coding: utf-8 -*-
from http.server import BaseHTTPRequestHandler,HTTPServer
from urllib.parse import urlparse, parse_qs
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
			self.wfile.write('{"error":true,"msg":"id has to be provided"}'.encode())
			return

		if 'sh' not in query_components:
			self.send_response(200)
			self.send_header('Content-type',"text/plain")
			self.end_headers()
			self.wfile.write('{"error":true,"msg":"Secret hash [sh] has to be provided"}'.encode())
			return

		if ''.join(query_components["sh"]) != self.settings['SECRET_HASH']:
			self.send_response(200)
			self.send_header('Content-type',"text/plain")
			self.end_headers()
			self.wfile.write('{"error":true,"msg":"Invalid secret hash"}'.encode())
			return

		if 'ct' not in query_components:
					self.send_response(200)
					self.send_header('Content-type',"text/plain")
					self.end_headers()
					self.wfile.write('{"error":true,"msg":"Context [ct] has to be provided"}'.encode())
					return

		if 'drop' in query_components:
			self.send_response(200)
			self.send_header('Content-type',"text/plain")
			self.end_headers()
			self.bot.dropDatabase(''.join(query_components["id"]), ''.join(query_components["ct"]))
			self.wfile.write('{"error":false,"msg":"Database was dropped"}'.encode())
			return

		if 'adddb' in query_components:
			self.send_response(200)
			self.send_header('Content-type',"text/plain")
			self.end_headers()
			self.bot.addDatabase(''.join(query_components["id"]), ''.join(query_components["ct"]))
			self.wfile.write('{"error":false,"msg":"Database was created"}'.encode())
			return

		if 'qd' in query_components:
			try:
				try:
					self.bot.deleteQuestion(''.join(query_components["id"]), ''.join(query_components["qd"]), ''.join(query_components["ct"]));
				except:
					pass
				self.send_response(200)
				self.send_header('Content-type',"text/plain")
				self.end_headers()
				self.wfile.write(json.dumps({"error":False,"msg":"question has been deleted"}).encode())
				return
	
			except IOError:
				self.send_error(404,'File Not Found: %s' % self.path)

		if 'qq' in query_components and 'qa' in query_components:
			try:
				self.bot.addAnswer(''.join(query_components["id"]), ''.join(query_components["qq"]), ''.join(query_components["qa"]), ''.join(query_components["ct"]));
				self.send_response(200)
				self.send_header('Content-type',"text/plain")
				self.end_headers()
				self.wfile.write(json.dumps({"error":False,"msg":"question with answer has been added"}).encode())
				return
	
			except IOError:
				self.send_error(404,'File Not Found: %s' % self.path)
	 
		if 'q' in query_components:
			try:
				self.send_response(200)
				self.send_header('Content-type',"text/plain")
				self.end_headers()
				answer = self.bot.getAnswer(''.join(query_components["id"]),''.join(query_components["q"]),''.join(query_components["ct"]))
				self.wfile.write(json.dumps({'error':False,'in_response':answer.in_response_to,'confidence':answer.confidence,'msg':answer.text}).encode())
				return
	
			except IOError:
				self.send_error(404,'File Not Found: %s' % self.path)
				
		self.send_response(200)
		self.send_header('Content-type',"text/plain")
		self.end_headers()
		self.wfile.write('{"error":true,"msg":"unknown operation"}'.encode())
		
		return