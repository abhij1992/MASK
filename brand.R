library(plyr)
library(stringr)
library(twitteR)

args <- commandArgs(TRUE)
brand1 <- args[1]
brand2 <- args[2]
#brand1<-"#samsung"
#brand2<-"#nokia"

consumer_key   <- "FauyFIvYxY0AWMZL6zZxyQI65"
consumer_secret<- "JhDIkfHGbrTYB0r2LItaylcQjlyXbBGsMK5AERSQZNEAzvshxE"
token <- "2549385872-6fhI4fBQkFrQnM2KMlBMqvvbZtuBl1HUVC59bj4" # From dev.twitter.com
token_secret <- "fjGlm1jpSGivReoFQKw7LNG2RTDsOI3NROj0xZZD9ek9K" # From dev.twitter.com

#make connection
setup_twitter_oauth(consumer_key = consumer_key,consumer_secret = consumer_secret,token, token_secret)




#get brand1 tweets
brand1_twt <- searchTwitter(brand1, n=100, lang="en")
brand1_Tweets.text = lapply(brand1_twt,function(t)t$getText())

#get brand2 tweets
brand2_twt <- searchTwitter(brand2, n=100, lang="en")
brand2_Tweets.text = lapply(brand2_twt,function(t)t$getText())


score.sentiment = function(sentences, pos.words, neg.words, .progress='none')
{
  require(plyr)
  require(stringr)
  # we got a vector of sentences. plyr will handle a list
  # or a vector as an "l" for us
  # we want a simple array ("a") of scores back, so we use
  # "l" + "a" + "ply" = "laply":
  scores = laply(sentences, function(sentence, pos.words, neg.words) {
    # clean up sentences with R's regex-driven global substitute, gsub():
    sentence = gsub('[[:punct:]]', '', sentence)
    sentence = gsub('[[:cntrl:]]', '', sentence)
    sentence = gsub('\\d+', '', sentence)
    # and convert to lower case:
    tryTolower = function(x)
    {
      
      y = NA
      try_error = tryCatch(tolower(x), error=function(e) e)
      if (!inherits(try_error, "error"))
        y = tolower(x)
      return(y)
    }
    sentence = sapply(sentence, tryTolower)
    # split into words. str_split is in the stringr package
    word.list = str_split(sentence, '\\s+')
    # sometimes a list() is one level of hierarchy too much
    words = unlist(word.list)
    # compare our words to the dictionaries of positive & negative terms
    pos.matches = match(words, pos.words)
    neg.matches = match(words, neg.words)
    # match() returns the position of the matched term or NA
    # we just want a TRUE/FALSE:
    pos.matches = !is.na(pos.matches)
    neg.matches = !is.na(neg.matches)
    # and conveniently enough, TRUE/FALSE will be treated as 1/0 by sum():
    score = sum(pos.matches) - sum(neg.matches)
	if(score<0)
    {
      score=-1
    }
    if(score>0)
    {
      score=1
    }
    return(score)
  }, pos.words, neg.words, .progress=.progress )
  scores.df = data.frame(score=scores, text=sentences)
  return(scores.df)
}

pos = scan('D:/wamp/www/MASK/R Scripts/positive-words.txt', what='character', comment.char=';')
neg = scan('D:/wamp/www/MASK/R Scripts/negative-words.txt', what='character', comment.char=';')
#pos = scan('C:/wamp/www/MASK/R Scripts/positive-words.txt', what='character', comment.char=';')
#neg = scan('C:/wamp/www/MASK/R Scripts/negative-words.txt', what='character', comment.char=';')

brand1analysis = score.sentiment(brand1_Tweets.text, pos, neg)
brand2analysis = score.sentiment(brand2_Tweets.text, pos, neg)

print(brand1)
cat("brand1-start")
table(brand1analysis$score)
cat("brand1-end\n")

print(brand2)
cat("brand2-start")
table(brand2analysis$score)
cat("brand2-end\n")



