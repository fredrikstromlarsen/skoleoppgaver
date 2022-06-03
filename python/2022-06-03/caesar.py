# Hovedoppgave: Løs kryptering.
# Hint: Ceasar cipher.
import string

def lett(ciphertext, alphabet):
    for i in range(len(alphabet)*-1, len(alphabet)):
        guess = []
        for x in ciphertext:
            letter = chr(ord(x)+i)
            if letter not in string.printable:
                break
            guess.append(letter)
        if len(guess) == len(ciphertext):
            print("".join(guess), "\ti:", i)

def vanskelig(ciphertext, alphabet, extra, min_percentage_writable):
    for i in range(len(alphabet)*-1, len(alphabet)):
        guess = []
        for x in ciphertext:
            if x in extra:
                guess.append(x)
            else:
                letter = chr(ord(x)+i)
                if letter in alphabet:
                    guess.append(letter)
                else:
                    guess.append(".")
        #if len(guess) == len(ciphertext):
        if guess.count(".") < len(guess) * (1-(min_percentage_writable / 100)):
            print("".join(guess), "\ti:", i)


if __name__ == '__main__':
    # Beskjed nummer 1
    message = "judwxohuhugxkdunrpphwodqjwscyhl"
    alphabet = "abcdefghijklmnopqrstuvwxyzæøåABCDEFGHIJKLMNOPQRSTUVWXYZÆØÅ"
    #lett(message, alphabet)

    ekstra = " !."
    # Beskjed nummer 2 - ekstra vanskelig
    message = "Judwxohuhu! Gx kdu noduw rssjdyhq vrp jlu ghw phjhw kbø pcorssqchovh l xwylnolqj sc YJV. Gx hu nodu iru vwbuuh rssjdyhu!"

    vanskelig(message, alphabet, ekstra, 94)