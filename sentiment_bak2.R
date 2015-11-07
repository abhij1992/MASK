library(twitteR)
library(plyr)
library(stringr)

args <- commandArgs(TRUE)
keyword <- args[1]

consumer_key   <- "FauyFIvYxY0AWMZL6zZxyQI65" #replace with your consumer_key
consumer_secret<- "JhDIkfHGbrTYB0r2LItaylcQjlyXbBGsMK5AERSQZNEAzvshxE" #replace with your consumer_key_secret
token <- "2549385872-6fhI4fBQkFrQnM2KMlBMqvvbZtuBl1HUVC59bj4" #Token_key
token_secret <- "fjGlm1jpSGivReoFQKw7LNG2RTDsOI3NROj0xZZD9ek9K" #Token_access_key 

#token and token secret values required to run this script without error
setup_twitter_oauth(consumer_key = consumer_key,consumer_secret = consumer_secret,access_token = token, access_secret = token_secret)



score.sentiment = function(sentences, pos.words, neg.words, .progress='none')
{
  #require(plyr)
  #require(stringr)
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
    return(score)
  }, pos.words, neg.words, .progress=.progress )
  scores.df = data.frame(score=scores, text=sentences)
  return(scores.df)
}

tweets <- searchTwitter(keyword, n=100, lang="en")
#we have to extract their text and save it into the variable tweets.text by typing:
Tweets.text = lapply(tweets,function(t)t$getText())

#pos = scan('D:/wamp/www/MASK/Web-Mask/positive-words.txt', what='character', comment.char=';')
#neg = scan('D:/wamp/www/MASK/Web-Mask/negative-words.txt', what='character', comment.char=';')
pos = scan('C:/wamp/www/MASK/Web-Mask/positive-words.txt', what='character', comment.char=';')
neg = scan('C:/wamp/www/MASK/Web-Mask/negative-words.txt', what='character', comment.char=';')



analysis = score.sentiment(Tweets.text, pos, neg)

#Tweets.text

cat("table-start")
print(table(analysis$score))
cat("table-end\n")



cat("mean-start")
mean(analysis$score)
cat("mean-end")

#to print Tweets
list<-Tweets.text
list = gsub('[[:cntrl:]]', '', list)
for(i in 1:10)
{
  cat(" tweet",i," ",sep ="")
  cat(list[i])
  cat(" tweetend",i,sep="")
}


#hist(analysis$score)