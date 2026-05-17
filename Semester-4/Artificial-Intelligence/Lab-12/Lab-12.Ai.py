import numpy as np
from PIL import Image
import random
import matplotlib.pyplot as plt
#Rifaq Ajmal
#23MDBCS 435
target_image = Image.open('grayscale.jpeg').convert('L')  # Load and convert to grayscale
target_image = target_image.resize((64, 64))  # Resize it if it's not already 64x64
target_pixels = np.array(target_image)

def create_random_image():
    return np.random.randint(0, 256, (64, 64), dtype=np.uint8)

population_size = 100  # You can change this based on the task requirements
population = [create_random_image() for _ in range(population_size)]

def fitness(image, target):
    return np.sum((image - target) ** 2)

def select_parents(population, target):
    fitness_scores = [fitness(individual, target) for individual in population]
    total_fitness = sum(fitness_scores)
    selection_probabilities = [score / total_fitness for score in fitness_scores]
    return random.choices(population, selection_probabilities, k=2)

def crossover(parent1, parent2):
    crossover_point = random.randint(1, 63)  # Random point to split the images
    offspring1 = np.vstack((parent1[:crossover_point], parent2[crossover_point:]))
    offspring2 = np.vstack((parent2[:crossover_point], parent1[crossover_point:]))
    return offspring1, offspring2

def mutate(image, mutation_rate=0.00000000000000000000001):
    for i in range(image.shape[0]):
        for j in range(image.shape[1]):
            if random.random() < mutation_rate:
                image[i][j] = random.randint(0, 255)  # Randomly change pixel value
    return image

def create_new_generation(population, target):
    new_population = []
    while len(new_population) < len(population):
        parent1, parent2 = select_parents(population, target)
        offspring1, offspring2 = crossover(parent1, parent2)
        new_population.append(mutate(offspring1))
        new_population.append(mutate(offspring2))
    return new_population[:len(population)]  # Make sure to match population size
generations = 100
best_images = []
fitness_values = []

for generation in range(generations):
    population = create_new_generation(population, target_pixels)
    
    # Track best image in the population
    best_individual = min(population, key=lambda x: fitness(x, target_pixels))
    best_images.append(best_individual)
    fitness_values.append(fitness(best_individual, target_pixels))
    
    # Save the best image of the generation
    best_image = Image.fromarray(best_individual)
    best_image.save(f"best_image_gen_{generation}.png")
    
    # Optionally display the fitness every few generations
    if generation % 10 == 0:
        print(f"Generation {generation}, Best Fitness: {fitness_values[-1]}")

# Plot fitness progress
plt.plot(fitness_values)
plt.title('Fitness Progress Over Generations')
plt.xlabel('Generation')
plt.ylabel('Fitness (MSE)')
plt.show()