games = {}
games['game1'] = { 'name' : 'PubG', 'genre':'Action Adventure', 'Release_year': 2018, 'rating' : 9.5}
games['game2'] = { 'name':'freefire', 'genre':'Action', 'Release_year': 2019, 'rating' : 8.5}
games['game3'] = { 'name':'call_of_Duty', 'genre':'Adventure', 'Release_year': 2009, 'rating' : 2.5}

for key in games:
    if games[key]['rating'] > 3.0:
        print(f"Details of {key}:")
        for key1 in games[key]:
            print(f"\t{key1}: {games[key][key1]}")
    else:
        print(f"{key} has a rating below 3.0 and won't be displayed.")
