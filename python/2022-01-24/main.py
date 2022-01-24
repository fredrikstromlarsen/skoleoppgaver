import mysql.connector as msql
from pprint import pprint
import pyfiglet

db = msql.connect(
    host="localhost",
    user="root",
    password="",
    database="Dromtorp"
)
cursor = db.cursor()
print(pyfiglet.figlet_format("MySQL + Python"))

def selectTable():
    cursor.execute("SHOW TABLES")
    result = cursor.fetchall()
    for i in range(len(result)):
        table = result[i]
        print("{index}. {table_name}".format(index=i+1, table_name=table[0]))
    tableSelected = ""
    while tableSelected not in result or tableSelected not in range(len(result)) + 1:
        tableSelected = int(input("Velg tabell: "))

    print(f"Åpner tabell nummer {tableSelected}.")
    openTable(*result[tableSelected - 1])

def openTable(table):
    print(f"Åpnet {table}.")

selectTable()