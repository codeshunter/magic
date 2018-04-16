# from django.shortcuts import render
import json
import pycurl

import datetime
from django.http.response import HttpResponse
from django.urls import reverse
from django.views.decorators.csrf import csrf_exempt
from io import BytesIO
from wechatpy import WeChatClient, parse_message, create_reply

from wechatpy.exceptions import InvalidSignatureException
from wechatpy.replies import ArticlesReply
from wechatpy.utils import check_signature, ObjectDict

from hellowechat.models import MagicUser, MagicRoom

test_appid = 'wx85c6baead26a17df'
test_secret = 'd31f7d14edd4a045ec41e04237c712b7'

appid = 'wx5b1d03fbc31a7d6e'
secret = '60185b0a026a8ea33dc1db02cf097aee'

client = WeChatClient(appid, secret)
test_client = WeChatClient(test_appid, test_secret)
WECHAT_TOKEN = 'weixin'


BASEURL = 'http://www.52hhu.com'
@csrf_exempt
def home(request):
    if request.method == 'GET':
        signature = request.GET.get('signature', '')
        timestamp = request.GET.get('timestamp', '')
        nonce = request.GET.get('nonce', '')
        echo_str = request.GET.get('echostr', '')
        try:
            check_signature(WECHAT_TOKEN, signature, timestamp, nonce)
        except InvalidSignatureException:
            echo_str = 'error'
        response = HttpResponse(echo_str, content_type="text/plain")
        return response
    elif request.method == 'POST':
        msg = parse_message(request.body)
        if msg.type == 'text' :
            muser = MagicUser.objects.get_or_create(openid=msg.source)
            print(muser[0].snumber)
            if '成绩' in msg.content and '更新' not in msg.content:
                reply = create_reply(str(muser[0].uuid) + str(msg.content), msg)
                print(str(muser[0].uuid))
                if None == muser[0].snumber:
                    url = BASEURL + reverse('index') + '?uuid=' + str(muser[0].uuid) + "&type=chengji&create=" + str(muser[1])
                else :
                    url = BASEURL + reverse('chengji') + '?uuid=' + str(muser[0].uuid)
                reply = ArticlesReply(message=msg)
                reply.add_article({
                    'title': '成绩',
                    'description': '成绩',
                    'image': BASEURL + '/static/mags/imgs/weixinmsggrade.png',
                    'url': url
                })
            elif '课表' in msg.content and '更新' not in msg.content :
                reply = create_reply(str(muser[0].uuid) + str(msg.content), msg)
                if None == muser[0].snumber:
                    url = BASEURL + reverse('index') + '?uuid=' + str(muser[0].uuid) + "&type=kebiao&create=" + str(muser[1])
                else :
                    url = BASEURL + reverse('kebiao') + '?uuid=' + str(muser[0].uuid)
                reply = ArticlesReply(message=msg)
                reply.add_article({
                    'title': '课表',
                    'description': '课表',
                    'image': 'http://pic1.sc.chinaz.com/files/pic/pic9/201803/zzpic10731.jpg',
                    'url': url
                })

            elif '更新成绩' in msg.content or '更新课表' in msg.content :
                if None == muser[0].snumber:
                    url = BASEURL + reverse('index') + '?uuid=' + str(muser[0].uuid) + "&type=chengji&create=" + str(muser[1])
                else :
                    url = BASEURL + reverse('gengxin') + '?uuid=' + str(muser[0].uuid)
                reply = ArticlesReply(message=msg)
                reply.add_article({
                    'title': '更新成绩和课表',
                    'description': '更新成绩和课表点击前往更新',
                    'image': 'http://pic1.sc.chinaz.com/files/pic/pic9/201803/zzpic10731.jpg',
                    'url': url
                })
            else:
                flag = False
                buildings = [
                    '致高楼A幢', '管理楼', '致用楼', '科学会堂',
                    '致高楼B幢', '水利馆', '闻天馆', '博学楼',
                    '工程馆', '北教楼', '致远楼', '江宁体育场',
                    '研究生综合楼', '水文楼', '励学楼', '勤学楼'
                ]
                for i in buildings :
                    if msg.content in i :
                        flag = True
                        break

                if flag :
                    d = datetime.datetime.now()
                    index = d.weekday()

                    #false空闲 true有课
                    #西康路校区 江宁校区 江宁西校区 常州校区

                    todayres = test_curl_fetch('http://map.hhu.edu.cn/mapi/api/v2.0/classRoom.json?day=' + str(index))
                    tomorrowres = test_curl_fetch('http://map.hhu.edu.cn/mapi/api/v2.0/classRoom.json?day=' + str((index + 1) % 7))

                    todayjisoshi = json.loads(todayres)['data']
                    tomorrowjisoshi = json.loads(tomorrowres)['data']

                    today = '您查询的教学楼为: {}\n今天空闲的教室有:'.format(msg.content)
                    tomorrow = '\n明天空闲的教室有:'.format(msg.content)

                    for i in todayjisoshi :
                        if msg.content in i['buildName'] :
                            today += i['code'] + ' '
                            for index, j in enumerate(i['lessons']) :
                                if not j :
                                    today += str(index + 1) + ' '
                            today += '小节|'

                    for i in tomorrowjisoshi :
                        if msg.content in i['buildName'] :
                            tomorrow += i['code'] + ' '
                            for index, j in enumerate(i['lessons']) :
                                if not j :
                                    today += str(index + 1) + ' '
                            tomorrow += '小节|'

                    reply = create_reply(today, msg)

                else :
                    reply = create_reply('暂不支持相关信息的查询', msg)

        elif msg.type == 'image':
            reply = create_reply('这是条图片消息', msg)
        elif msg.type == 'voice':
            reply = create_reply('这是条语音消息', msg)
        else:
            reply = create_reply('这是条其他类型消息', msg)
        response = HttpResponse(reply.render(), content_type="application/xml")
        return response


def test_curl_fetch (url) :
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