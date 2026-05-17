def f(x):
    return x**3 - x - 2

def bisection(a, b, tol=1e-6):
    if f(a)*f(b) >= 0:
        print("Invalid interval")
        return

    while (b - a) / 2 > tol:
        c = (a + b) / 2
        if f(c) == 0:
            break
        elif f(a)*f(c) < 0:
            b = c
        else:
            a = c

    print("Root:", c)

bisection(1, 2)