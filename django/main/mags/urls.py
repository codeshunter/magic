# -*- coding: utf-8 -*-
from django.conf.urls import include, url
from . import views

urlpatterns = [
    url(r'^$', views.index, name='index'),
    url(r'^success$', views.success),
    url(r'^fail', views.fail, name='bind_failed'),
    url(r'^binder', views.binder, name='binder'),
    url(r'^vcode', views.getCode, name='vcode'),
    url(r'^clogin', views.curlLogIn, name='clogin'),
    url(r'^testUUID', views.testUUID, name='testUUID'),
    url(r'^chengji', views.chengji, name='chengji'),
    url(r'^kebiao', views.kebiao, name='kebiao'),
    url(r'^gengxin', views.gengxin, name='gengxin'),
    url(r'^cungengxin', views.cungengxin, name='cungengxin'),
    url(r'^emptyroom', views.emptyroom, name='emptyroom'),
    url(r'^sendmail', views.sendmail, name='sendmail'),
]