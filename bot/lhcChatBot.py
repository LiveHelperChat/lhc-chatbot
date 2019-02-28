from lhc_chatbot import ChatBot

import logging
from chatterbot.trainers import ListTrainer
from chatterbot.comparisons import jaccard_similarity
from chatterbot.comparisons import SentimentComparison
from chatterbot.comparisons import SynsetDistance
from chatterbot.comparisons import LevenshteinDistance
from chatterbot.response_selection import get_random_response


from collections import UserDict
from collections import MutableMapping as DictMixin
from collections import deque

jaccard_similarity.SIMILARITY_THRESHOLD=0.5

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

    def __len__(self):
        return len(self._keys)

    def __iter__(self):
        for i in self._keys:
            yield i

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
	
	def getBot(self, instanceId, context = "0"):
	
		self.cleanupBot()
	
		if instanceId+"-"+context in self.botInstance:
			return self.botInstance[instanceId+"-"+context]
	
		self.botInstance[instanceId+"-"+context] = ChatBot('Terminal',
		    storage_adapter='lhc_sql_storage.LiveHelperChatSQLStorageAdapter',
		    logic_adapters=[
               {
                "import_path": "best_lhc_match.BestLiveHelperChatMatch",
                "statement_comparison_function":jaccard_similarity,
                "default_response": "noreply",
                "maximum_similarity_threshold" : 0.4,
                "response_selection_method": get_random_response
               }
            ],
		    read_only=True,
		    database_uri=self.settings['DB_URL']+self.settings['DATABASE_PREFIX'] + "-" + instanceId+"-"+context
			)
		return self.botInstance[instanceId+"-"+context]

	def getAnswer(self, instanceId, question, context = "0"):
		bot = self.getBot(instanceId, context)
		return bot.get_response(question)

	def addAnswer(self, instanceId, question, answer, context = "0"):
		bot = self.getBot(instanceId, context)
		bot.storage.read_only = False
		trainer = ListTrainer(bot)
		trainer.train([
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

	def addDatabase(self, instanceId, context = "0"):
		bot = self.getBot(instanceId, context)
		bot.storage.create_database()

	
	def showBot(self):
		print("show bot")	