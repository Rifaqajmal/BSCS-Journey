import random

# Function to get the user's choice
def get_user_choice():
    user_choice = input("Enter rock, paper, or scissors: ").lower()
    return user_choice

# Function to get the computer's random choice
def get_computer_choice():
    choices = ['rock', 'paper', 'scissors']
    computer_choice = random.choice(choices)
    return computer_choice

# Function to decide the winner
def decide_winner(user, computer):
    if user == computer:
        return "It's a tie!"
    elif (user == 'rock' and computer == 'scissors') or \
         (user == 'scissors' and computer == 'paper') or \
         (user == 'paper' and computer == 'rock'):
        return "You win!"
    else:
        return "Computer wins!"

# Main game function
def play_game():
    user_choice = get_user_choice()
    computer_choice = get_computer_choice()

    print("You chose:", user_choice)
    print("Computer chose:", computer_choice)

    result = decide_winner(user_choice, computer_choice)
    print(result)

# Run the game
play_game()
