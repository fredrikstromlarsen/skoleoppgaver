import string
import random


def random_user_id(length, amount):
    chars = string.ascii_letters + string.digits
    uidList = []
    for i in range(amount):
        uid = ""
        for j in range(length):
            uid = uid + chars[random.randint(0, len(chars) - 1)]
        uidList.append(uid)
    return uidList

def random_color(amount):
    colors = []
    for i in range(amount):
        colors.append(f"rgb({random.randint(0, 255)},{random.randint(0, 255)},{random.randint(0, 255)})")
    return colors