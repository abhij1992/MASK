library(twitteR)
consumer_key   <- "FauyFIvYxY0AWMZL6zZxyQI65"
consumer_secret<- "JhDIkfHGbrTYB0r2LItaylcQjlyXbBGsMK5AERSQZNEAzvshxE"
token <- "2549385872-6fhI4fBQkFrQnM2KMlBMqvvbZtuBl1HUVC59bj4" # From dev.twitter.com
token_secret <- "fjGlm1jpSGivReoFQKw7LNG2RTDsOI3NROj0xZZD9ek9K" # From dev.twitter.com
setup_twitter_oauth(consumer_key = consumer_key,consumer_secret = consumer_secret,token, token_secret)

args <- commandArgs(TRUE)
keyword <- args[1]

tweets <- searchTwitter(keyword, n=100, lang="en")
Tweets.text = lapply(tweets,function(t)t$getText())

#install.packages (c ( "tm", "wordcloud"))
library (tm)
library (wordcloud)
sentence = gsub('[[:punct:]]', '', Tweets.text)
#sentence = gsub('<', '', sentence)
sentence = gsub('[[:cntrl:]]', '', sentence)
sentence = gsub('\\d+', '', sentence)
sentence = gsub('http', '', sentence)
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
(ovid <- VCorpus(VectorSource(sentence),readerControl = list(language = "lat")))
tm_map (ovid, removePunctuation) # remove punctuations
tm_map (ovid, removeNumbers) # to remove numbers
#tm_map (ovid, removeWords, stopwords('english')) # to remove stop words(like 'as' 'the' etc..)
myStopwords = c(stopwords('english'), "available", "via");
idx = which(myStopwords == "r");
myStopwords = myStopwords[-idx];
myCorpus = tm_map(ovid, removeWords, myStopwords);
#writeLines(as.character(sentence2[[2]]))
#inspect(ovid[1:2])
#writeLines(as.character(ovid[[2]]))
#writeLines(as.character(Tweets.text[2]))
Matrix <- TermDocumentMatrix(myCorpus) # terms in rows
DTM <- DocumentTermMatrix(myCorpus) # document no's in rows
m <- as.matrix(DTM)
v <- sort(colSums(m),decreasing=TRUE)
cat("table-start")
head(v,14)
cat("table-end\n")

filename=paste("wordclouds/",format(Sys.time(), "%Y.%m.%d.%s"),".png",sep="")
words <- names(v)
d <- data.frame(word=words, freq=v)
png(filename, width=4, height=4, units="in", res=300)
wordcloud(d$word,d$freq,min.freq=5)
dev.off() #only 129kb in size
cat("filename-start")
filename
cat("filename-end\n")
