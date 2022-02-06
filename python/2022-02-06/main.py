import math
r = 0
a = float(input("Number to calculate the root from: \n"))
b = float(input("Exponent to use: \n"))
c = float("1" + "0" * math.floor(math.log10(math.sqrt(a))))
while (r + 0.00001) ** b < a:
    if (r + c) ** b < a:
        r += c
    else:
        c /= 10
print(f"{b} √ {a}\n= {r}")