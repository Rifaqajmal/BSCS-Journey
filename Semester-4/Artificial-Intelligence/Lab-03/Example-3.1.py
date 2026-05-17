#create a dictionary inside the dictionary

games={}
games['game1'] = { 'name' : 'PubG', 'genre':'Action Adventure ','Release_year': 2018, 'rating' :9.5}
games['game2'] = { 'name':'freefire', 'genre':'Action','Release_year': 2019, 'rating' :8.5}
games['game3'] = { 'name':'call_of_Duty', 'genre':'Adventure ','Release_year': 2009, 'rating' :2.5}
for key in games:
    print (key)
    for key1 in  games[key]:
        print ('\t',games[key][key1])

