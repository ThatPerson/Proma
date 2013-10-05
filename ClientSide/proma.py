#!/usr/bin/python2
import urllib2
import sys
import hashlib
import os
#response = urllib2.urlopen('http://python.org/')
#html = response.read()

#print(len(sys.argv))
if (len(sys.argv) == 4):
	if (sys.argv[1] == "connect"):
		print("Connecting...")
		name = sys.argv[2]
		url = sys.argv[3]
		req = urllib2.Request(url)
		try: 
			urllib2.urlopen(req)
		except URLError as e:
			print "Connecting failed"
			exit(0)
		print("Connection worked.")
		auth = 0
		tries = 0
		while (auth == 0 and tries < 3):
			if tries != 0:
				print("Connecting failed")
			username = raw_input("Username: ")
			password = raw_input("Password: ")
			username = hashlib.md5(username).hexdigest()
			password = hashlib.md5(password).hexdigest()
			urloe =url+"?u="+username+"&p="+password
			req = urllib2.Request(url+"?u="+username+"&p="+password)
			#//#print(urloe)
			try: 
				lor = urllib2.urlopen(req)
				tries = tries+1
				if (lor.read() == ""):
					auth = 1
			
			except URLError as e:
			
				tries = tries+1
	
		if (auth != 1):
			print("Password wrong")
			exit(0)
		#print(os.getenv("HOME"))
		with open(os.getenv("HOME")+"/"+".proma.txt", "a") as myfile:
			myfile.write(name+"<^>"+url+"<^>"+username+"<^>"+password+"\n")
		print("Done.")
		exit(0)
	
f = open(os.getenv("HOME")+"/"+".proma.txt", 'r')
l = []
for line in f:
	l.append(line.split("<^>"))
	
if (len(sys.argv) >= 4):
	#name = sys.argv[1]
	item = -1
	for i in range(0, len(l)):
		if (l[i][0] == sys.argv[1]):
			item = i
	if (item == -1):
		print("No connection")
		exit(0)
		
	if (sys.argv[2] == "view"):
		if (sys.argv[3] == "todo"):
			url = l[item][1]+"?u="+l[item][2]+"&p="+l[item][3].replace("\n", "")+"&vito"
			#print(url)
			response = urllib2.urlopen(url)
			html = response.read()
			content = html.split("<br>")
			for i in range(0, len(content)):
				print(content[i])
		if (sys.argv[3] == "posts"):
			url = l[item][1]+"?u="+l[item][2]+"&p="+l[item][3].replace("\n", "")+"&vibl"
			response = urllib2.urlopen(url)
			html = response.read()
			content = html.split("<br>")
			for i in range(0, len(content)):
				print(content[i])
	if (sys.argv[2] == "new"):
		if (sys.argv[3] == "todo"):
			stringl = ""
			for i in range(4, len(sys.argv)):
				stringl = stringl+"%20"+sys.argv[i]
			url = l[item][1]+"?u="+l[item][2]+"&p="+l[item][3].replace("\n", "")+"&todo="+stringl
			response = urllib2.urlopen(url)
			html = response.read()
			print(html)
		if (sys.argv[3] == "post"):
			stringl = ""
			conte = sys.argv[4]
			f = open(conte, 'r')
			fe = f.read()
			fe = fe.replace("\n", "<br>")
			fe = fe.replace(" ", "%20")
			for i in range(5, len(sys.argv)):
				stringl = stringl+"%20"+sys.argv[i]
			title = stringl
			url = l[item][1]+"?u="+l[item][2]+"&p="+l[item][3].replace("\n", "")+"&title="+title+"&blog="+fe
			response = urllib2.urlopen(url)
			html = response.read()
			print(html)
	if (sys.argv[2] == "complete"):
		url = l[item][1]+"?u="+l[item][2]+"&p="+l[item][3].replace("\n", "")+"&complete="+sys.argv[3]
		response = urllib2.urlopen(url)
		html = response.read()
		print(html)
	if (sys.argv[2] == "delete"):
		if (sys.argv[3] == "post"):
			url = l[item][1]+"?u="+l[item][2]+"&p="+l[item][3].replace("\n", "")+"&blro="+sys.argv[4]
			response = urllib2.urlopen(url)
			html = response.read()
			print(html)
		
else:
	print("No arguments")
	
