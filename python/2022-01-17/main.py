import sys

print(f"Python version {sys.version}")
print(f"Version info {sys.version_info}")

num0 = "2"
if isinstance(num0, int):
    print(f"{num0} er en integer.")
else:
    print(f"{num0} er ikke en integer.")


def multiplication(a, b):
    return a * b


def division_multi(a, b, c):
    return multiplication(a, b) / c


num0 = 89
num1 = 13

print(f"{num0} * {num1} = {multiplication(num0, num1)}")

num0 = 20
num1 = 10
num2 = 4
result = division_multi(num0, num1, num2)

print(f"{num0} * {num1} / {num2} = {result} . Resultatet er en {type(result)}")