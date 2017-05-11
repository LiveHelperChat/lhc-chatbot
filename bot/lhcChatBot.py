from chatterbot import ChatBot
import logging
from chatterbot.trainers import ListTrainer
from UserDict import DictMixin
from collections import deque

class odict(DictMixin):
    
    def __init__(self):
        self._keys = deque([])
        self._data = {}
        
    def __setitem__(self, key, value):
        if key not in self._data:
            self._keys.append(key)
        self._data[key] = value
        
        
    def __getitem__(self, key):
        return self._data[key]
    
    
    def __delitem__(self, key):
        del self._data[key]
        self._keys.remove(key)
        
        
    def keys(self):
        return list(self._keys)
    
    
    def copy(self):
        copyDict = odict()
        copyDict._data = self._data.copy()
        copyDict._keys = self._keys[:]
        return copyDict
        
        
class lhcChatBot:
	
	settings = None
	botInstance = None
	
	def __init__(self):
		self.botInstance = odict()		
	
	def cleanupBot(self):
		if len(self.botInstance) > 10:						
			key = self.botInstance._keys.popleft()
			del self.botInstance._data[key]
			print key
	
	def getBot(self,instanceId):
	
		self.cleanupBot()
	
		if instanceId in self.botInstance:			
			return self.botInstance[instanceId]
	
		self.botInstance[instanceId] = ChatBot('Terminal',
		    storage_adapter='chatterbot.storage.MongoDatabaseAdapter',
		    logic_adapters=[
		        'chatterbot.logic.BestMatch'
		    ],
		    filters=[
		        'chatterbot.filters.RepetitiveResponseFilter'
		    ],
		    read_only=True,
		    database=self.settings['DATABASE_PREFIX'] + "-" + instanceId
			)

		return self.botInstance[instanceId]		

	def getAnswer(self, instanceId, question):
		bot = self.getBot(instanceId)
		return bot.get_response(question)

	def addAnswer(self, instanceId, question, answer):
		bot = self.getBot(instanceId)
		bot.storage.read_only = False
		bot.set_trainer(ListTrainer)				
		bot.train([
		question,
		answer
		])
		bot.storage.read_only = True
	
	def deleteQuestion(self, instanceId, question):
		bot = self.getBot(instanceId)
		bot.storage.read_only = False			
		bot.storage.remove(question)
		bot.storage.read_only = True
	
	def dropDatabase(self, instanceId):
		bot = self.getBot(instanceId)		
		bot.storage.drop()
		if instanceId in self.botInstance:			
			del self.botInstance[instanceId]
	
	def showBot(self):
		print "show bot"	