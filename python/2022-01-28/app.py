from flask import Flask
from markupsafe import escape
import mysql.connector

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
    cursor = db.cursor()
except mysql.connector.Error as e:
    print(f"Could not connect to \"{database}\" database on \"{host}\" with username \"{user}\"\r"
          f"Full error message:\r\r{e}")
    exit(1)

app = Flask(__name__)


@app.route("/")
def index():
    cursor.execute("SELECT * FROM elev")
    result = cursor.fetchall()
    html = f"<code>{result}</code>"
    html += "<table border=1>"
    for i in range(len(result)):
        html += "<tr>"
        for n in range(len(result[i])):
            html += f"<td style='padding: 0.25rem;'>{result[i][n]}</td>"
        html += "</tr>"
    html += "</table>"
    return html


@app.route("/delete")
def delete():
    return"<h1>Delete</h1>"


@app.route("/data/<table>/<name>")
def show_user(name):
    return f"<h1>Hello {escape(name)}</h1>"