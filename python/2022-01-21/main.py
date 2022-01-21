# Oppgave e)
def printResult(task, varName, result):
    print(f"Oppgave {task}:\n\t{varName} = {result}\n")
    
    
# Oppgave a)
test = 8
printResult("a", "test", test)

# Oppgave b)
test = "testverdi"
print(f"test er en {type(test)}")
printResult("b", "test", test)

# Oppgave c)
produkt = 2 * 3
printResult("c", "produkt", produkt)

# Oppgave d)
broek = 2 / 3
printResult("d", "broek", broek)