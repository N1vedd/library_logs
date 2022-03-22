from flask import Flask , render_template , request , redirect , url_for , session
from flask_sqlalchemy import SQLAlchemy
from datetime import datetime , timedelta , date
from flask_wtf import FlaskForm
from wtforms import StringField,SubmitField,DateTimeField,DateField,SelectField
from wtforms.validators import DataRequired 
from sqlalchemy import desc 
from pandas import date_range


app = Flask(__name__)
app.config['SQLALCHEMY_DATABASE_URI']='mysql+pymysql://root:password@localhost:3307/library'
app.config['SECRET_KEY']='dumb password'
db=SQLAlchemy(app)
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
	date = DateField("Date",format='%Y-%m-%d',validators = [DataRequired()])
	name = StringField("Name",validators = [DataRequired()])
	roll_no = StringField("Roll No",validators = [DataRequired()])
	designation = SelectField(u'Designation', choices=[('student', 'Student'), ('staff', 'Staff')],validators = [DataRequired()])
	check_in = StringField("Check-in",validators = [DataRequired()])
	submit = SubmitField("Submit")

class punch_out(FlaskForm):
	roll_no = StringField("Roll No",validators = [DataRequired()])
	check_out = StringField("Check-out",validators = [DataRequired()])
	submit = SubmitField("Submit")

class statsinfo(FlaskForm):
	roll_no = StringField("Roll No",validators = [DataRequired()])
	s_date = DateField("from",validators = [DataRequired()])
	e_date = DateField("till",validators = [DataRequired()])
	submit = SubmitField("Submit",validators = [DataRequired()])

class logsinfo(FlaskForm):
	s_date = DateField("from",validators = [DataRequired()])
	e_date = DateField("till",validators = [DataRequired()])
	submit = SubmitField("Submit",validators = [DataRequired()])


@app.route('/')
def home():
	return render_template("home.html")

@app.route('/update',methods=["GET","POST"])
def update():
	form=punch_in()
	form2=punch_out()
	if form2.validate_on_submit():
		condition1=log.query.filter(log.roll_no==form2.roll_no.data, log.check_out==None).first()
		if condition1 is not None:
			entry=condition1.check_in
			entry_h=entry.strftime("%H")
			entry_m=entry.strftime("%M")
			exit=form2.check_out.data
			exit=''.join(filter(str.isdigit, exit))
			exit_h=int(exit[:2])
			exit_m=int(exit[2:4])
			h=(exit_h-int(entry_h))*60
			m=exit_m-int(entry_m)
			total_time=h+m
			if total_time<0:
				return '<h1>the username wasnt updated successfully </h1>'
			update=log.query.filter(log.roll_no==form2.roll_no.data).order_by(desc(log.column_not_exist_in_db)).first()
			update.check_out=form2.check_out.data
			update.time_spent=total_time

			db.session.commit()
		else:
			return '<h1>the username wasnt updated successfully </h1>'
	
	if form.validate_on_submit():
		condition2=log.query.filter(log.roll_no==form.roll_no.data, log.check_out==None).first()
		if condition2 is None:
			log_data=log(date=form.date.data,name=form.name.data,roll_no=form.roll_no.data,designation=form.designation.data,check_in=form.check_in.data)
			db.session.add(log_data)
			db.session.commit()
		else:
			return '<h1>the username wasnt inserted successfully </h1>'
	form.name.data=''
	form.roll_no.data=''
	form.check_in.data=''
	form2.roll_no.data=''
	form2.check_out.data=''
	our_users=log.query.order_by(desc(log.column_not_exist_in_db))	
	
	return  render_template("update.html",form=form,form2=form2,our_users=our_users)


@app.route('/statistics',methods=["POST","GET"])
def statistics():
	dates=[]
	datas=[]
	entries=0
	form=statsinfo()
	if form.validate_on_submit():
		sdate=form.s_date.data
		edate=form.e_date.data
		raw_dates=date_range(sdate,edate,freq='d')
		dates=[]
		for i in raw_dates:
			dates.append(i.strftime("%d:%m:%Y"))

		session['dates']=[form.s_date.data,form.e_date.data]

		for dt in raw_dates:
			total_time_spent=0
			command=log.query.filter(log.roll_no==form.roll_no.data,log.date==dt,log.time_spent!=None).order_by(log.date)
			for t_data in command:
				total_time_spent+=t_data.time_spent
				entries+=1
			datas.append(total_time_spent)
		user_data=[form.roll_no.data,entries]

		session['datas'] = datas
		session['user_data'] = user_data
		return redirect(url_for('stats'))
		
	return  render_template("statistics.html",form=form,dates=dates,datas=datas)

@app.route('/stats')
def stats():
	datas=session.get('datas',None)
	dates=session.get('dates',None)
	user_data=session.get('user_data',None)
	session.pop('datas',None)
	session.pop('dates',None)
	session.pop('user_data',None)
	sdate=datetime.strptime(dates[0][:-4],'%a, %d %b %Y %H:%M:%S')
	edate=datetime.strptime(dates[1][:-4],'%a, %d %b %Y %H:%M:%S')
	raw_dates=date_range(sdate,edate-timedelta(days=1),freq='d')
	dates=[i.strftime("%d:%m:%Y") for i in raw_dates]
	return render_template("stats.html",datas=datas,dates=dates,user_data=user_data,total=sum(datas))

@app.route('/logs_info',methods=["POST","GET"])
def logs_info():
	form=logsinfo()
	if form.validate_on_submit():
		session['sdate']=form.s_date.data
		session['edate']=form.e_date.data
		return redirect(url_for('logs'))
	return render_template("logs_info.html",form=form)

@app.route('/logs',methods=["POST","GET"])
def logs():
	sdate=session.get('sdate',None)
	edate=session.get('edate',None)
	session.pop('sdate',None)
	session.pop('edate',None)
	sdate=datetime.strptime(sdate[:-4],'%a, %d %b %Y %H:%M:%S')
	edate=datetime.strptime(edate[:-4],'%a, %d %b %Y %H:%M:%S')
	log_data=log.query.filter(log.date>=sdate,log.date<=edate).order_by(log.column_not_exist_in_db)
	return render_template("logs.html",log_data=log_data)