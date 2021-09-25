Crawls IKEA API for availability of Blåhajar.
Run scripts using crontab. 

getBlahajStats.php: Saves raw stock data as JSON files
compareBlahaj.php: Mastodon bot to print Blåhaj status
writeToDatabase.php: Writes stock to MySQL database
writeToTerminal.php: Prints stock data to the terminal
IkeaStoresExtractor.py: Gets a list of IKEA stores, run when new stores are opened
config.php: Configuration data for passwords etc.

Run getBlahajStats.php and then the other programs once every hour