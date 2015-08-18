library(twitteR)
consumer_key   <- "uhlGuejAGkwE9rQVEwJVoWMlG"
consumer_secret<- "iI9jVlCwntyfJe5YnAF3qFSDhimfgajWTAA0kK9Qp3nAFsQuf9"
setup_twitter_oauth(consumer_key = consumer_key,consumer_secret = consumer_secret,access_token = NULL, access_secret = NULL)



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
    return(score)
  }, pos.words, neg.words, .progress=.progress )
  scores.df = data.frame(score=scores, text=sentences)
  return(scores.df)
}

tweets <- searchTwitter("#GOT", n=1000, lang="en")
#we have to extract their text and save it into the variable tweets.text by typing:
Tweets.text = lapply(tweets,function(t)t$getText())

pos = scan('C:/Users/Joshua/Desktop/Project MASK/MASK/R Scripts/positive-words.txt', what='character', comment.char=';')
neg = scan('C:/Users/Joshua/Desktop/Project MASK/MASK/R Scripts/negative-words.txt', what='character', comment.char=';')



analysis = score.sentiment(Tweets.text, pos, neg)


table(analysis$score)

mean(analysis$score)

hist(analysis$score)