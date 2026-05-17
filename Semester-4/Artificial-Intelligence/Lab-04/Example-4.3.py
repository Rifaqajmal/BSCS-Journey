# Read the file and split each line into a list of game information
lines = [line.strip() for line in open('scores.txt')]
games = [line.split(',') for line in lines]

# Initialize variables to keep track of the biggest difference and game info
biggest_diff = 0
game_info = []

# Loop through each game to find the biggest margin of victory
for g in games:
    team1_score = int(g[2])  # Score of the first team
    team2_score = int(g[4])  # Score of the second team
    diff = abs(team1_score - team2_score)  # Calculate the margin of victory

    if diff > biggest_diff:  # If current margin is larger, update biggest_diff
        biggest_diff = diff
        game_info = g  # Store the game information

# Output the game information with the biggest margin of victory
print(f"Most lopsided game: {game_info}")
print(f"Date: {game_info[0]}")
print(f"Teams: {game_info[1]} vs {game_info[3]}")
print(f"Score: {game_info[1]} {game_info[2]} - {game_info[3]} {game_info[4]}")
print(f"Margin of Victory: {biggest_diff}")
