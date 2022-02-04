from flask import Flask

app = Flask(__name__)


@app.route('/')
def root():
    return "Denne siden kjører HTTPS"


if __name__ == '__main__':
    app.run(ssl_context='adhoc')