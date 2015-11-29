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

badtweet<-"one"
goodtweet<-"two"
eachtweet<-"hello"
maxbad<-0
worsttweet<-""
maxgood<-0
besttweets<-""
score.sentiment = function(sentences, pos.words, neg.words, .progress='none')
{
  scores = laply(sentences, function(sentence, pos.words, neg.words) {
    # clean up sentences with R's regex-driven global substitute, gsub():
    eachtweet <<- sentence
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
      badtweet<<-c(badtweet,eachtweet)
      if (score<maxbad)
      {
        worsttweet<<-""
        maxbad<<-(score)
      }
      if(score == maxbad)
      {
        worsttweet<<-c(worsttweet,eachtweet)
      }
      
    }
    if(score>0)
    {
      goodtweet<<-c(goodtweet,eachtweet)
      #score=-1
      
      if (score>maxgood)
      {
        besttweets<<-""
        maxgood<<-score
      }
      if(score == maxgood)
      {
        besttweets<<-c(besttweets,eachtweet)
      }
      
    }
    #print(sentence)
    #print("\nbatman\n")
    return(score)
  }, pos.words, neg.words, .progress=.progress )
  scores.df = data.frame(score=scores, text=sentences)
  return(scores.df)
}


tweets <- searchTwitter(keyword, n=100, lang="en")
tweets2<-strip_retweets(tweets, strip_manual = TRUE, strip_mt = TRUE)

#remove duplicates
#texts <- sapply( unlist( tweets2 ) , function(x) `$`( x , "text" ) )
#length(texts)
#unq <- texts[ ! duplicated( texts ) ]
#length(unq.texts)

#we have to extract their text and save it into the variable tweets.text by typing:
Tweets.text = lapply(tweets2,function(t)t$getText())


pos = scan('D:/wamp/www/MASK/Web-Mask/positive-words.txt', what='character', comment.char=';')
neg = scan('D:/wamp/www/MASK/Web-Mask/negative-words.txt', what='character', comment.char=';')
#pos = scan('C:/wamp/www/MASK/Web-Mask/positive-words.txt', what='character', comment.char=';')
#neg = scan('C:/wamp/www/MASK/Web-Mask/negative-words.txt', what='character', comment.char=';')

#old one without removing duplicates
#analysis = score.sentiment(Tweets.text, pos, neg)

#with removing duplicates
analysis = score.sentiment(Tweets.text, pos, neg)

#Tweets.text

cat("table-start")
print(table(analysis$score))
cat("table-end\n")



cat("mean-start")
mean(analysis$score)
cat("mean-end")

#hist(table(analysis$score))

#list<-Tweets.text
#list = gsub('[[:cntrl:]]', '', list)

besttweets<-besttweets[2:length(besttweets)]
#head(besttweets,5)
#to print Tweets
list<-head(besttweets,5)
cat("best-tweet")
cat("<div class='container-twt'>
<a href='#' class='tw-icon-twt'></a>
			<div class='notification-box-twt'>")
for(i in 1:length(besttweets))
{
  #cat("<div id='tweet'>")
  
	cat("		
				<div class='list-twt'>
					<img src='images/twitter-logo.jpg' class='avatar-twt'>
					<div class='content-twt'>
					")
	cat(list[i])
	cat("<i class='time-twt'>1s</i>
					</div>
					</div>
					
			
	")
  
  #cat("</div>")
}
cat("</div></div>")
cat("best-tweet-end\n")


worsttweet<-worsttweet[2:length(worsttweet)]
#cat("worst-tweet")
#head(worsttweet,5)
#cat("worst-tweet-end\n")
list<-head(worsttweet,5)
cat("worst-tweet")
cat("<div class='container-twt'>
<a href='#' class='tw-icon-twt'></a>
			<div class='notification-box-twt'>")
for(i in 1:length(worsttweet))
{
  #cat("<div id='tweet'>")
  cat("
			
				<div class='list-twt'>
					<img src='images/twitter-logo.jpg' class='avatar-twt'>
					<div class='content-twt'>
					")
	cat(list[i])
	cat("<i class='time-twt'>1s</i>
					</div>
					</div>
					
			
	")
  
  
}
cat("</div></div>")
cat("worst-tweet-end\n")

