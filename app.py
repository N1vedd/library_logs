from flask import Flask , render_template
#create a flask instance
app = Flask(__name__)
#Create a route decorator
@app.route('/')

def index():
	return "<h1>Sup <h1>"

@app.route('/user/<name>')
def user(name):
	pets = ["dog","cat","penguin","cow"]
	return render_template("index.html",name=name,pets=pets)