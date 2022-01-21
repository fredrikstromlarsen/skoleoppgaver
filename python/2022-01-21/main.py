# 1.
# e)
def print_result(task, varName, result):
    print(f"\nOppgave {task}:")
    for n in range(len(varName)):
        print(f"\t{varName[n]} = {result[n]}")


# a)
test = 8
print_result("1a", ["test"], [test])

# b)
test = "testverdi"
print_result("1b", ["test", "type(test)"], [test, type(test)])

# c)
produkt = 2 * 3
print_result("1c", ["produkt"], [produkt])

# d)
broek = 2 / 3
print_result("1d", ["broek"], [broek])


# 2.
# a)


def calc_area_rect(h, w):
    return h * w


calc_area_rect(8, 8)

# b)


def calc_area_triangle(h, w):
    return h * w / 2


# c)


def calc_area(h, w):
    triangle = h * w / 2
    rectangle = h * w

    products = [triangle, rectangle]
    return products

h = 9
w = 17

print_result("2c", ["Høyde", "Bredde", "Areal av trekant", "Areal av firkant"], [h, w, *calc_area(h, w)])


# 3
# a)
# input()-funksjonen gjør at brukeren kan velge hva verdien i f.eks. en variabel skal være.
# I parentesene kan man skrive hva man vil skal stå foran input-feltet.
# Den gir kun variabeltyper string, så for å gjøre om til f.eks. integer, må man caste med int()-funksjonen:
    # a = int(input("Skriv et tall"))

# b)
def nationality():
    print("\ts = Svensk\n\tn = Norsk\n\td = Dansk")
    short_hand = input(f"\tHva er din nasjonalitet? (d/n/s): ")
    if short_hand == "s":
        nationality = "svensk"
    elif short_hand == "n":
        nationality = "norsk"
    elif short_hand == "d":
        nationality = "dansk"
    else:
        return print(f"\t{short_hand} er ikke et gyldig svar.")
    return print(f"\n\tDu er {nationality}")


print("\nOppgave 3b:")
nationality()

# 4
print("\nOppgave 4:")

import random as r
import sys as s

answer = r.randint(0, 50)
guess = int(input("\tGjett tallet jeg tenker på (0-50): "))
while True:
    if answer == guess:
        print("\tRiktig!")
        break;
    elif answer < guess:
        action = "Lavere"
    else:
        action = "Høyere"
    guess = int(input(f"\t{action}, prøv igjen; "))

# 5
# Metoden som blir brukt går som følger:
# 0-50: 25
# Høyere.
# Nå som vi vet at den er høyere, vil minimumverdien være 26, og maks vil være 50.
# Halvparten av 26-50 = 12. Halvparten av gyldige tall er da 26+12 = 38.
# 0-50: 38
# Lavere.
# Rinse and repeat.
# 0-50: 32
# Lavere.
# 0-50: 29
# Høyere.
# 0-50: 30
# Riktig!