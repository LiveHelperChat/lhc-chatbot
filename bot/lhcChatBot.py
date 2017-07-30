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
		if len(self.botInstance) > 3:						
			key = self.botInstance._keys.popleft()
			del self.botInstance._data[key]
			print key
	
	def getBot(self, instanceId, context = "0"):
	
		self.cleanupBot()
	
		if instanceId+"-"+context in self.botInstance:
			return self.botInstance[instanceId+"-"+context]
	
		self.botInstance[instanceId+"-"+context] = ChatBot('Terminal',
		    storage_adapter='chatterbot.storage.MongoDatabaseAdapter',
		    logic_adapters=[
		        {
		            'import_path': 'chatterbot.logic.BestMatch'
		        },
		        {
		            'import_path': 'chatterbot.logic.LowConfidenceAdapter',
		            'threshold': 0.51,
		            'default_response': 'notfound'
		        }
		    ],
		    read_only=True,
		    database=self.settings['DATABASE_PREFIX'] + "-" + instanceId+"-"+context
			)

		return self.botInstance[instanceId+"-"+context]		

	def getAnswer(self, instanceId, question, context = "0"):
		bot = self.getBot(instanceId, context)
		return bot.get_response(question)

	def addAnswer(self, instanceId, question, answer, context = "0"):
		bot = self.getBot(instanceId, context)
		bot.storage.read_only = False
		bot.set_trainer(ListTrainer)				
		bot.train([
		question,
		answer
		])
		bot.storage.read_only = True
	
	def deleteQuestion(self, instanceId, question, context = "0"):
		bot = self.getBot(instanceId, context)
		bot.storage.read_only = False			
		bot.storage.remove(question)
		bot.storage.read_only = True
	
	def dropDatabase(self, instanceId, context = "0"):
		bot = self.getBot(instanceId, context)		
		bot.storage.drop()
		if instanceId+"-"+context in self.botInstance:			
			del self.botInstance[instanceId+"-"+context]
	
	def showBot(self):
		print "show bot"	