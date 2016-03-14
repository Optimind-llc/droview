import React, { PropTypes, Component } from 'react';
//Config
import { _ADMIN_DOMAIN_NAME } from '../../../config/env';
//Components
import { Avatar, Divider, List, ListItem } from 'material-ui';
import Colors from 'material-ui/lib/styles/colors';
import ContentInbox from 'material-ui/lib/svg-icons/content/inbox';
import { SelectableContainerEnhance } from 'material-ui/lib/hoc/selectable-enhance';
let SelectableList = SelectableContainerEnhance(List);

function wrapState(ComposedComponent) {
  class StateWrapper extends Component {  
    constructor(props) {
      super(props);
      this.state = {
        selectedIndex: props.pathname
      };
    }

    handleUpdateSelectedIndex(e, index) {
      this.props.push(index);
      this.setState({
        selectedIndex: index,
      });
    }

    render() {
      return (
        <ComposedComponent
          {...this.props}
          {...this.state}
          valueLink={{
            value: this.state.selectedIndex,
            requestChange: this.handleUpdateSelectedIndex.bind(this)
          }}
        />
      );
    }
  }

  StateWrapper.propTypes = {
    push: PropTypes.func.isRequired,
  };

  return StateWrapper;
}

SelectableList = wrapState(SelectableList);

class MainSidebar extends Component {
  render() {
    const { pathname, push } = this.props;
    const styles = {
      innerDiv: {
        paddingLeft: 50,
        fontSize: '1.5rem',
        textAlign: 'left'  
      },
      icon: {
        height: 17,
        margin: 16
      },
    }
    const path = {
      dashboard: `${_ADMIN_DOMAIN_NAME}dashboard`,
      access: `${_ADMIN_DOMAIN_NAME}access/users`,
      plans: `${_ADMIN_DOMAIN_NAME}flight/plans`,
      types: `${_ADMIN_DOMAIN_NAME}flight/types`,
      places: `${_ADMIN_DOMAIN_NAME}flight/places`,
      pinList: `${_ADMIN_DOMAIN_NAME}pins/list`,
      pinGenerate: `${_ADMIN_DOMAIN_NAME}pins/generate`,
    }
    return(
      <SelectableList pathname={pathname} push={push}>
        <ListItem
          disabled={true}
          primaryText="shiichi saito"
          leftAvatar={<Avatar src="images/ok-128.jpg" />}/>
        <Divider />
        <ListItem
          id="dashboard"
          value={path.dashboard}
          primaryText="Dashboard"
          innerDivStyle={styles.innerDiv}
          leftIcon={<ContentInbox style={styles.icon}/>}/>

        <ListItem
          id="access"
          value={path.access}
          primaryText="Access Management"
          innerDivStyle={styles.innerDiv}
          leftIcon={<ContentInbox style={styles.icon}/>}/>

        <ListItem
          autoGenerateNestedIndicator={false}
          nestedListStyle={{margin: 0}}
          primaryText="Flight Management"
          innerDivStyle={styles.innerDiv}
          leftIcon={<ContentInbox style={styles.icon}/>}
          initiallyOpen={false}
          primaryTogglesNestedList={true}
          nestedItems={[
            <ListItem
              value={path.plans}
              primaryText="Plans"
              innerDivStyle={styles.innerDiv}
              leftIcon={<ContentInbox style={styles.icon}/>}/>,
            <ListItem
              value={path.types}
              primaryText="Types"
              innerDivStyle={styles.innerDiv}
              leftIcon={<ContentInbox style={styles.icon}/>}/>,
            <ListItem
              value={path.places}
              primaryText="places"
              innerDivStyle={styles.innerDiv}
              leftIcon={<ContentInbox style={styles.icon}/>}/>
          ]}
        />
        <ListItem
          id="pin"
          value={path.pinList}
          primaryText="Access Management"
          innerDivStyle={styles.innerDiv}
          leftIcon={<ContentInbox style={styles.icon}/>}/>
      </SelectableList>  
    )  
  }
}

MainSidebar.propTypes = {
  push: PropTypes.func.isRequired,
};

export default MainSidebar;
