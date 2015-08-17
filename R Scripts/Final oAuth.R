library(twitteR)
setup_twitter_oauth(consumer_key = consumer_key,consumer_secret = consumer_secret,access_token = NULL, access_secret = NULL)
tweets <- searchTwitter("Obamacare OR ACA OR 'Affordable Care Act' OR #ACA", n=10, lang="en", since="2014-08-20")