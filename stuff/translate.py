# pip install -u pipenv

import pyttsx3
import csv

id = 0

def run(lang,id,text):

    engine = pyttsx3.init() # object creation



    voice = lang
    sentence = text

    """ RATE"""
    rate = engine.getProperty('rate')   # getting details of current speaking rate
                                        #printing current voice rate
    engine.setProperty('rate', 125)     # setting up new voice rate


    """VOLUME"""
    volume = engine.getProperty('volume')   #getting to know current volume level (min=0 and max=1)
                             #printing current volume level
    engine.setProperty('volume',1.0)    # setting up volume level  between 0 and 1

    """VOICE"""
    voices = engine.getProperty('voices')       #getting details of current voice


    #engine.setProperty('voice', voices[0].id)  #changing index, changes voices. o for male
    engine.setProperty('voice', voices[voice].id)   #changing index, changes voices. 1 for female

    engine.save_to_file(sentence, 'audio/' + str(id) + '.wav')

    engine.say(sentence)
    engine.runAndWait()

with open('data.csv', newline='', encoding='utf-8') as csvfile:
    spamreader = csv.reader(csvfile, delimiter=',', quotechar='"')
    for row in spamreader:

        #print( row[0])
        #print( row[1])

        run(1,id + 1,row[0])
        run(2,id + 2,row[1])

        id = id + 2



