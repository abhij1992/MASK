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
head(v,14)
words <- names(v)
d <- data.frame(word=words, freq=v)
wordcloud(d$word,d$freq,min.freq=50)