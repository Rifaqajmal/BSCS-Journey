#Variable Arguments Example:

def product(*nums):
    prod = 1
    for i in nums:
        prod *= i
    return prod

print(product(3, 4))
print(product(2, 3, 4))
