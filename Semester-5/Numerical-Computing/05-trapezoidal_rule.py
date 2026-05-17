def f(x):
    return x**2

def trapezoidal(a, b, n):
    h = (b - a) / n
    s = f(a) + f(b)

    for i in range(1, n):
        s += 2 * f(a + i*h)

    result = (h/2) * s
    print("Integral:", result)

trapezoidal(0, 2, 100)