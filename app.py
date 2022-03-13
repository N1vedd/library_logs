from flask import Flask , render_template , request
from flask_sqlalchemy import SQLAlchemy
from datetime import datetime
from flask_wtf import FlaskForm
from wtforms import StringField,SubmitField,DateTimeField,DateField
from wtforms.validators import DataRequired  

app = Flask(__name__)
app.config['SQLALCHEMY_DATABASE_URI']='mysql+pymysql://root:password@localhost:3307/library'
app.config['SECRET_KEY']='dumb password'
db=SQLAlchemy(app)
curr_users=[]
class log(db.Model):
	column_not_exist_in_db = db.Column(db.Integer, primary_key=True)
	date=db.Column(db.Date,nullable=False)
	roll_no=db.Column(db.String(255),nullable=False)
	name=db.Column(db.String(255),nullable=False)
	designation=db.Column(db.String(255),nullable=False)
	check_in=db.Column(db.Time,nullable=False)
	check_out=db.Column(db.Time,nullable=True)
	time_spent=db.Column(db.Integer,nullable=True)

	def __repr__(self):
		return '<Name %r>' % self.name


class punch_in(FlaskForm):
	date = DateField("Date:-",validators = [DataRequired()])
	name = StringField("Name:-",validators = [DataRequired()])
	roll_no = StringField("Roll No:-",validators = [DataRequired()])
	designation = StringField("Designation:-",validators = [DataRequired()])
	check_in = DateTimeField("Check-in:-",validators = [DataRequired()])
	submit = SubmitField("Submit")



@app.route('/',methods=["GET","POST"])


def index():
	form=punch_in()

	if form.validate_on_submit():
		log_data=log(date="2000:12:12",name="lol",roll_no="80",designation="student",check_in="19:09:09")
		db.session.add(log_data)
		db.session.commit()
		return redirect(url_for('register'))
	our_users=log.query.order_by(log.date)	

	return  render_template("index.html",form=form,our_users=our_users)

@app.route('/index')
def hi():
	return "hi"