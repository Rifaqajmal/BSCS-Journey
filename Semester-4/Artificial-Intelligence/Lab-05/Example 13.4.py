# Function to convert Celsius to Fahrenheit
def convert(t):
    return t * 9 / 5 + 32


# Function to print a message, with default and keyword arguments
def fancy_print(text, color='black', background='white', style='normal', justify='left'):
    print(f"Text: {text}, Color: {color}, Background: {background}, Style: {style}, Justify: {justify}")

    

# Example usage
print(convert(20))  # Converts 20°C to Fahrenheit
print(convert(30) + 5)  # Converts 30°C to Fahrenheit and adds 5 to the result


# Calling fancy_print with default arguments

fancy_print("Hello")  # Uses all default arguments
fancy_print("Hello", style="bold")  # Changes style to bold using keyword argument
fancy_print("Hello", color="yellow", background="black", style="italic", justify="center")  # All arguments with keyword
