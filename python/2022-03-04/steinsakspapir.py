from random import randint


def play_manual():
    print("\n1. Stein\n2. Saks\n3. Papir\n")
    action = -1
    while action not in range(3) and action is not None:
        action = int(input("Skriv et tall for å velge en av handlingene ovenfor: ")) - 1
    return action


def play_automated(mode):
    # Sett handling (stein, saks eller 
    # papir) til tilfeldig eller statisk 
    # (samme handling hver gang).
    if mode == 0:
        return randint(0, 2)  
    else:
        return mode
        

def play_game(play_type=0, auto_mode=-1):
    actions_list = ["stein", "saks", "papir"]
    if play_type == 0:
        player_action = play_manual()
    else:
        if auto_mode == -1:
            print("Hvordan skal spillet automatiseres?\n0. Tilfeldig\n1-3. Stein/saks/papir hele tiden\n")
            while auto_mode not in range(0, 4) and auto_mode is not None:
                auto_mode = int(input("Velg en av modi over."))
        player_action = play_automated(auto_mode)
    bot_action = randint(0, 2)
    win_table = [[0, 1, 2], [2, 0, 1], [1, 2, 0]]
    return win_table[player_action][bot_action]


def win_percentage(log):
    wins = log.count(1)
    total_games = len(log)
    if wins == 0:
        return 0
    else:
        return round(wins * 100 / total_games, 2)


if __name__ == '__main__':
    win_log = []
    count = 1000
    for i in range(count):
        win_log.append(play_game(1, 0))
    print(f"Du har vunnet {win_log.count(1)} av {len(win_log)} ({win_percentage(win_log)}%) spill")