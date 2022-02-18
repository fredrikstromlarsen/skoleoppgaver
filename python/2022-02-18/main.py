import random
from pprint import pprint
import math

randomNumbers = [random.randint(1, 50) for i in range(20)]
pprint(randomNumbers)


def a(arr):
    sum = 0
    for i in arr:
        sum += i
    return sum


print(f"a) Summen av alle tall i listen er: {a(randomNumbers)}\n")


def b(arr):
    sorted_arr = sorted(arr)
    arr_len = len(arr)
    first = sorted_arr[math.floor((arr_len - 1) / 2)]
    second = sorted_arr[math.ceil((arr_len - 1) / 2)]
    median = (first + second) / 2
    return median


print(f"b) Medianen i listen er: {b(randomNumbers)}\n")


def c(arr):
    return a(arr) / len(arr)


print(f"c) Gjennomsnittet i listen er: {c(randomNumbers)}\n")


def d(arr, i):
    return arr[i]


print(f"d) Index 4 i listen er: {d(randomNumbers, 3)}, Index 18 i listen er: {d(randomNumbers, 17)}\n")


def e(arr):
    return max(arr)


print(f"e) Det høyeste tallet i listen er: {e(randomNumbers)}\n")


def f(arr):
    return list(dict.fromkeys(arr))


print(f"f) Listen uten duplikater:")
pprint(f(randomNumbers))
print("")


def g(arr):
    return arr[-5::]


print(f"g) De siste 5 elementene i listen er: {g(randomNumbers)}\n")


def h(arr):
    half = arr[0::2]
    return a(half)


print(f"h) Summen av annenhvert tall i listen er: {h(randomNumbers)}\n")


def i(arr, indexes, values):
    for i in range(len(indexes)):
        arr[indexes[i]] = values[i]
    return arr


print(f"i) Gammel liste øverst og ny liste nederst.")
pprint(randomNumbers)
pprint(i(randomNumbers,[3, 6, 15], [1, 2, 3]))
print("")


def j(arr):
    arr.append(random.randint(0, 50))
    return arr


print(f"j) Et nytt tall er lagt til i slutten av listen:")
pprint(j(randomNumbers))
print("")


def k(arr, multiplicand, max_val):
    products = []
    multiplier = 0
    for multiplier in range(math.floor(max_val/multiplicand)):
        products.append(multiplicand * multiplier)

    return products

print("k) 3-gangeren er som følger:")
pprint(k(randomNumbers, 3, 100))
print("")