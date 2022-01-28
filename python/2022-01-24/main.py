import mysql.connector
from pprint import pprint
import pyfiglet
from terminaltables import AsciiTable

host = "localhost"
user = "root"
password = ""
database = "Dromtorp"

try:
    db = mysql.connector.connect(
        host=host,
        user=user,
        password=password,
        database=database
    )
except mysql.connector.Error as e:
    print(f"Could not connect to \"{database}\" database on \"{host}\" with username \"{user}\"\r" \
          f"Full error message:\r\r{e}")
    exit(1)

cursor = db.cursor()
print(pyfiglet.figlet_format("MySQL + Python"))


def select_table():
    cursor.execute("SHOW TABLES")
    result = cursor.fetchall()
    for i in range(len(result)):
        table = result[i]
        print(f"{i + 1}. {table[0]}")
    table_selected = ""
    print("\n")
    while table_selected == "":
        table_selected = int(input("Velg tabell: "))
    show_table_contents(result[table_selected - 1][0])


def show_table_contents(table):
    print(f"\nÅpnet {table}.")
    cursor.execute(f"SELECT * FROM {table}")
    result = cursor.fetchall()
    print(f"Innhold i {table}: \n")
    cursor.execute(f"SELECT `COLUMN_NAME` FROM `INFORMATION_SCHEMA`.`COLUMNS` WHERE `TABLE_SCHEMA`='{database}' AND `TABLE_NAME`='{table}';")
    result_columns = cursor.fetchall()
    table_columns = []
    for i in range(len(result_columns)):
        table_columns.append(result_columns[i][0])
    data = list(result)
    table_data = [table_columns, *data]
    table = AsciiTable(table_data)
    print(table.table + "\n")
    select_table()


select_table()