
file1 = open('ftemps.txt', 'w')
temperatures = [line.strip() for line in open('temps.txt')]

for t in temperatures:
    # Convert the string temperature to a float
    t = float(t)
    # Convert Celsius to Fahrenheit and write to the file
    print(t * 9/5 + 32, file=file1)

file1.close()

with open('ftemps.txt', 'r') as ftemps:
    print("Temperatures in Fahrenheit:")
    for line in ftemps:
        print(line.strip())
