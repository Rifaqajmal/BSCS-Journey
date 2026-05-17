import random

# 1. Generating a random integer between two numbers using randint
random_int = random.randint(1, 100)
print("Random Integer between 1 and 100:", random_int)

# 2. Shuffling a list of numbers using shuffle
my_list = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10]
random.shuffle(my_list)
print("Shuffled List:", my_list)

# 3. Getting a random sample from a list using sample
sample_list = random.sample(my_list, 3)  # get 3 random elements from the list
print("Random Sample of 3 elements:", sample_list)
