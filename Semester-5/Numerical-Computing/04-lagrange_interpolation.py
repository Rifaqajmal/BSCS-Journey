def lagrange(x, y, xp):
    n = len(x)
    yp = 0

    for i in range(n):
        p = 1
        for j in range(n):
            if i != j:
                p *= (xp - x[j]) / (x[i] - x[j])
        yp += p * y[i]

    return yp


x = [1, 2, 3]
y = [1, 4, 9]

xp = 2.5
print("Interpolated value:", lagrange(x, y, xp))