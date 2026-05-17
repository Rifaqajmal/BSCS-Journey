Operator Example:

python
Copy
from operator import add
from functools import reduce
total = reduce(add, range(1, 101))  # Sum numbers from 1 to 100
print(total)
