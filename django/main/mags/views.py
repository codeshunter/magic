import json
import random
import pycurl
import base64
from io import BytesIO
from urllib.parse import urlencode
import re


from django.core.mail import send_mail
from bs4 import BeautifulSoup
from django.http import HttpResponse
from django.shortcuts import render, redirect
# Create your views here.
from django.urls import reverse

from hellowechat.models import MagicUser

# cookiedir = 'ctmp/'
cookiedir = '/opt/workspace/djangomagic/magic/ctmp/'

def firstindex (request) :
    return render(request, 'mags/firstindex.html')

def index (request):
    uuid = request.GET['uuid']
    type = request.GET['type']

    if type == 'False' :
        return redirect(reverse(type) + "?uuid=" + uuid)
    return render(request, 'mags/index.html', {
        'uuid': uuid,
        'type': type
    })

def gengxin (request) :
    uuid = request.GET['uuid']
    return render(request, 'mags/gengxin.html', {
        'uuid': uuid
    })

def cungengxin (request) :
    uuid = request.POST['uuid']
    print('------------' + uuid)
    user = MagicUser.objects.get(uuid=uuid)
    snumber = user.snumber
    spasswd = base64.b64decode(user.spasswd).decode()

    # type = request.POST['type']

    rescode = login(snumber, spasswd, request.POST['stucode'], uuid)
    if (rescode == 0):

        grades = curl_fetch('http://jwxt1.hhu.edu.cn/bxqcjcxAction.do', uuid)
        lessons = curl_fetch('http://jwxt1.hhu.edu.cn/xkAction.do?actionType=6', uuid)

        gradessoup = BeautifulSoup(grades, "html.parser")
        lessonssoup = BeautifulSoup(lessons, "html.parser")
        gradetrs = gradessoup.find(id='user').find_all('tr')
        lessontrs = lessonssoup.find(id='user').find_all('tr')
        lessonindextrs = lessonssoup.find_all(id='user')[1].find_all('tr')

        sumPoint = 0.0  # 绩点 * 学分之和
        sumWeight = 0.0  # 总学分
        gradesdata = []
        lessonsdata = [([''] * 8) for i in range(12)]

        for gtr in gradetrs:
            cells = gtr.find_all('td')
            if (len(cells) > 0):
                gradedata = {
                    'bixuan': strdel(cells[5].get_text()),
                    'lessonname': strdel(cells[2].get_text()),
                    'weight': strdel(cells[4].get_text()),
                    'score': strdel(cells[9].get_text()),
                    'mingci': strdel(cells[10].get_text())
                }
                if gradedata['bixuan'] == '必修' and gradedata['score'] != '':
                    sumWeight += float(gradedata['weight'])
                    beforePoint = gradedata['score']
                    if (beforePoint == '优秀' or beforePoint == '良好' or beforePoint == '缺考' or
                                beforePoint == '中等' or beforePoint == '合格' or beforePoint == '不合格'):
                        afterPoint = changeDesc2Point(beforePoint)
                    else:
                        afterPoint = changeScore2Point(float(beforePoint))

                    sumPoint += float(gradedata['weight']) * afterPoint
                gradesdata.append(gradedata)

        jsongrade = json.dumps(gradesdata)

        lessonnames = []
        lessonname = ""
        for index, litr in enumerate(lessonindextrs):
            cells = litr.find_all('td')
            if len(cells) > 7:
                lessonname = strdel(cells[2].get_text()) + '_' + strdel(cells[3].get_text())

            lessonnames.append(lessonname)

        print(lessonnames)

        for index, litr in enumerate(lessonindextrs):
            if index > 0:
                cells = litr.find_all('td')
                if len(cells) > 7:
                    cells = cells[11:18]

                print(cells[1])
                if (strdel(cells[1].get_text())) != '':
                    info = lessonnames[index] + strdel(cells[0].get_text()) + \
                           strdel(cells[4].get_text()) + \
                           strdel(cells[5].get_text()) + \
                           strdel(cells[6].get_text()) + '|'
                    colindex = int(strdel(cells[1].get_text()))
                    lineindex = jie2index(int(strdel(cells[2].get_text())))
                    print(info, lineindex, colindex)
                    num = int(cells[3].get_text())

                    for times in range(num):
                        lessonsdata[lineindex + times][colindex] += info

        jsonlesson = json.dumps(lessonsdata)
        jidian = get2nums(sumPoint / sumWeight)

        user.sgrades = jsongrade
        user.slessons = jsonlesson
        user.sjidian = jidian
        user.snumber = snumber
        user.spasswd = base64.b64encode(spasswd.encode())
        user.save()
        return render(request, 'mags/success.html')
    else:
        return render(request, 'mags/fail.html')

def panduan (request) :
    return ''

def success (request):
    return render(request, 'mags/success.html')


def fail (request):
    return render(request, 'mags/fail.html')


def binder (request):
    return redirect(reverse('bind_failed'))

def chengji (request) :
    uuid = request.GET['uuid']
    user = MagicUser.objects.get(uuid=uuid)
    grades = json.loads(user.sgrades)
    return render(request, 'mags/grade.html', {
        'grades': grades,
        'snumber': user.snumber,
        'jidian': user.sjidian
    })


def kebiao (request) :
    uuid = request.GET['uuid']
    user = MagicUser.objects.get(uuid=uuid)
    slessons = json.loads(user.slessons)
    # strlesson = str(slessons)
    return render(request, 'mags/lesson.html', {
        'lessons': slessons,
        'indexs': [1,2,3,4,5,6,7]
    })

def getCode (request):
    uuid = request.GET['uuid']
    url = 'http://jwxt1.hhu.edu.cn/validateCodeAction.do?random=' + str(random.random())
    c = pycurl.Curl()
    c.setopt(pycurl.COOKIEJAR, cookiedir + uuid + ".cookie")
    c.setopt(pycurl.FOLLOWLOCATION, 1)  # 允许跟踪来源
    c.setopt(pycurl.MAXREDIRS, 5)

    head = ['Accept:*/*',
            'User-Agent:Mozilla/5.0 (Windows NT 6.1; WOW64; rv:32.0) Gecko/20100101 Firefox/32.0']
    c.fp = BytesIO()
    c.setopt(pycurl.WRITEFUNCTION, c.fp.write)
    c.setopt(pycurl.URL, url)
    c.setopt(pycurl.HTTPHEADER, head)
    c.perform()
    return HttpResponse(c.fp.getvalue(), content_type='image/jpeg')


def curlLogIn (request):
    '''
    zjh:1504010232
    mm:1504010232
    v_yzm:kgyt
    '''
    snumber = request.POST['stuid']
    spasswd = request.POST['stupwd']
    uuid = request.POST['uuid']
    type = request.POST['type']

    rescode = login(snumber, spasswd, request.POST['stucode'], uuid)
    if (rescode == 0):
        user = MagicUser.objects.get(uuid=request.POST['uuid'])

        grades = curl_fetch('http://jwxt1.hhu.edu.cn/bxqcjcxAction.do', uuid)
        lessons = curl_fetch('http://jwxt1.hhu.edu.cn/xkAction.do?actionType=6', uuid)

        gradessoup = BeautifulSoup(grades, "html.parser")
        lessonssoup = BeautifulSoup(lessons, "html.parser")
        gradetrs = gradessoup.find(id='user').find_all('tr')
        lessontrs = lessonssoup.find(id='user').find_all('tr')
        lessonindextrs = lessonssoup.find_all(id='user')[1].find_all('tr')

        sumPoint = 0.0  # 绩点 * 学分之和
        sumWeight = 0.0  # 总学分
        gradesdata = []
        lessonsdata = [([''] * 8) for i in range(12)]

        for gtr in gradetrs :
            cells = gtr.find_all('td')
            if (len(cells) > 0) :
                gradedata = {
                    'bixuan': strdel(cells[5].get_text()),
                    'lessonname': strdel(cells[2].get_text()),
                    'weight': strdel(cells[4].get_text()),
                    'score': strdel(cells[9].get_text()),
                    'mingci': strdel(cells[10].get_text())
                }
                if gradedata['bixuan'] == '必修' and gradedata['score'] != '':
                    sumWeight += float(gradedata['weight'])
                    beforePoint = gradedata['score']
                    if (beforePoint == '优秀' or beforePoint == '良好' or beforePoint == '缺考' or
                                beforePoint == '中等' or beforePoint == '合格' or beforePoint == '不合格'):
                        afterPoint = changeDesc2Point(beforePoint)
                    else :
                        afterPoint = changeScore2Point(float(0 if beforePoint == '' else beforePoint))

                    sumPoint += float(gradedata['weight']) * afterPoint
                gradesdata.append(gradedata)

        jsongrade = json.dumps(gradesdata)

        lessonnames = []
        lessonname = ""
        for index, litr in enumerate(lessonindextrs):
            cells = litr.find_all('td')
            if len(cells) > 7 :
                lessonname = strdel(cells[2].get_text()) + '_' + strdel(cells[3].get_text())

            lessonnames.append(lessonname)

        print(lessonnames)

        for index, litr in enumerate(lessonindextrs) :
            if index > 0 :
                cells = litr.find_all('td')
                if len(cells) > 7 :
                    cells = cells[11:18]

                print(cells[1])
                if (strdel(cells[1].get_text())) != '' :
                    info = lessonnames[index] + strdel(cells[0].get_text()) + \
                           strdel(cells[4].get_text()) + \
                           strdel(cells[5].get_text()) + \
                           strdel(cells[6].get_text()) + '|'
                    colindex = int(strdel(cells[1].get_text()))
                    lineindex = jie2index(int(strdel(cells[2].get_text())))
                    print(info,lineindex,colindex)
                    num = int(cells[3].get_text())

                    for times in range(num) :
                        lessonsdata[lineindex + times][colindex] += info

        jsonlesson = json.dumps(lessonsdata)
        if sumWeight == 0 :
            jidian = 0
        else :
            jidian = get2nums(sumPoint / sumWeight)

        user.sgrades = jsongrade
        user.slessons = jsonlesson
        user.sjidian = jidian
        user.snumber = snumber
        user.spasswd = base64.b64encode(spasswd.encode())
        user.save()
        # return HttpResponse(jidian)
        return render(request, 'mags/success.html')
    else :
        return render(request, 'mags/fail.html')


def testUUID (request) :
    # # for i in range(7) :
    # print('fetching day ' + request.GET['i'])
    # strres = test_curl_fetch('http://map.hhu.edu.cn/mapi/api/v2.0/classRoom.json?day=' + request.GET['i'], request.GET['uuid'])
    # room = MagicRoom(day=request.GET['i'], detail=strres)
    # # room.save()
    data = [([i] * 8) for i in range(12)]
    return render(request, 'mags/test.html', {
        'data': data,
        'index': 3
    })

def test_curl_fetch (url, uuid) :
    curl = pycurl.Curl()

    head = ['Accept:*/*',
            'User-Agent:Mozilla/5.0 (Windows NT 6.1; WOW64; rv:32.0) Gecko/20100101 Firefox/32.0']

    buf = BytesIO()
    curl.setopt(pycurl.COOKIEFILE, cookiedir + uuid + ".cookie")
    curl.setopt(pycurl.WRITEFUNCTION, buf.write)
    curl.setopt(pycurl.URL, url)
    curl.setopt(pycurl.HTTPHEADER, head)
    curl.perform()
    the_page = buf.getvalue()
    # print the_page
    buf.close()
    str = the_page.decode("UTF-8");
    return str

def strdel (str) :
    return re.sub(re.compile(r'[\s]'), '', str)


def jie2index (i) :
    sdict = {
        1: 0,
        2: 2,
        3: 5,
        4: 7,
        5: 9
    }
    return sdict[i]


def removekh (str) :
    # return str.split('(')[0]
    return str

def curl_fetch (url, uuid) :
    curl = pycurl.Curl()

    head = ['Accept:*/*',
            'User-Agent:Mozilla/5.0 (Windows NT 6.1; WOW64; rv:32.0) Gecko/20100101 Firefox/32.0']

    buf = BytesIO()
    curl.setopt(pycurl.WRITEFUNCTION, buf.write)
    curl.setopt(pycurl.COOKIEFILE, cookiedir  + uuid + ".cookie")
    curl.setopt(pycurl.POSTFIELDS, urlencode({}))
    curl.setopt(pycurl.URL, url)
    curl.setopt(pycurl.HTTPHEADER, head)
    curl.perform()
    the_page = buf.getvalue()
    # print the_page
    buf.close()
    str = the_page.decode("GBK");
    return str;

def login (snum, smm, syzm, uuid) :
    ddata = {
        'zjh': snum,
        'mm': smm,
        'v_yzm': syzm
    }
    data = urlencode(ddata)
    print(data)
    curl = pycurl.Curl()
    url = 'http://jwxt1.hhu.edu.cn/loginAction.do'

    head = ['Accept:*/*',
            'User-Agent:Mozilla/5.0 (Windows NT 6.1; WOW64; rv:32.0) Gecko/20100101 Firefox/32.0']

    buf = BytesIO()
    curl.setopt(pycurl.WRITEFUNCTION, buf.write)
    curl.setopt(pycurl.COOKIEFILE, cookiedir + uuid + ".cookie")
    curl.setopt(pycurl.POSTFIELDS, data)
    curl.setopt(pycurl.URL, url)
    curl.setopt(pycurl.HTTPHEADER, head)
    curl.perform()
    the_page = buf.getvalue()
    # print the_page
    buf.close()
    str = the_page.decode("GBK")
    # print(str)
    if ('验证码错误' in str) :
        return -1
    elif ('登录' in str) :
        return -2
    else :
        return 0


def changeScore2Point(score):
    if score >= 90:
        return 5.0
    elif score >= 85:
        return 4.5
    elif score >= 80:
        return 4.0
    elif score >= 75:
        return 3.5
    elif score >= 70:
        return 3.0
    elif score >= 65:
        return 2.5
    elif score >= 60:
        return 2.0
    elif score < 60:
        return 0.0


def changeDesc2Point(degree):
    if degree == '优秀':
        return 5.0
    if degree == '良好':
        return 4.5
    if degree == '中等':
        return 3.5
    if degree == '合格':
        return 2.5
    if degree == '不合格' or degree == '缺考':
        return 0.0


def get2nums (num) :
	return int(num * 100 + 0.5) / 100


def curl_fetch_without_cookie (url) :
    curl = pycurl.Curl()

    head = ['Accept:*/*',
            'User-Agent:Mozilla/5.0 (Windows NT 6.1; WOW64; rv:32.0) Gecko/20100101 Firefox/32.0']

    buf = BytesIO()
    curl.setopt(pycurl.WRITEFUNCTION, buf.write)
    # curl.setopt(pycurl.COOKIEFILE, cookiedir + uuid + ".cookie")
    curl.setopt(pycurl.URL, url)
    curl.setopt(pycurl.HTTPHEADER, head)
    curl.perform()
    the_page = buf.getvalue()
    # print the_page
    buf.close()
    str = the_page.decode("UTF-8");
    return str


def emptyroom (request) :
    day = request.GET['day']
    todayres = curl_fetch_without_cookie('http://map.hhu.edu.cn/mapi/api/v2.0/classRoom.json?day=' + str(day))
    return HttpResponse(todayres)


def sendmail (request) :
    data = json.loads(request.body.decode())
    name = data['name']
    wechat = data['wechat']
    phone = data['phone']
    detail = data['detail']

    if name == '' or wechat == '' or phone == '' or detail == '' or name == None or wechat == None or phone == None or detail == None :
        return HttpResponse('fail')

    content = '姓名：{}\n微信：{}\n手机号：{}\n合作事宜：{}'.format(name, wechat, phone, detail)
    send_mail('合作事宜', content, 'family_studio@126.com', ['18112900163@163.com', '01117360@wisedu.com'], fail_silently=False)
    return HttpResponse('success')